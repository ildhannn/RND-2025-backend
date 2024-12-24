<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserControllers extends Controller
{
    public function getAllUser(Request $request)
    {
        $user['total'] = User::get()->count();
        $user['aktif'] = User::where('status', 1)->get()->count();
        $user['tidak_aktif'] = User::where('status', 0)->get()->count();
        $user['users'] = User::get();
        // $user['users'] = $allUser->faceRecognize;

        $logController = app(LogControllers::class);
        $logController->addToLog('Menampilkan list user');

        return $this->sendResponse(200, 'List User', $user, 200);
    }

    public function getIdUser($id)
    {
        $user = User::findOrFail($id);
        return $this->sendResponse(200, 'User ' . $id . ' Ditemukan', $user, 200);
    }

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors());
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'alamat' => $request->alamat,
            'status' => 0,
            'foto' => $request->foto,
            'url_foto' => $request->url_foto,
            'kewenangan_id' => 3,
        ]);

        $logController = app(LogControllers::class);
        $logController->addToLog('Membuat User Baru');

        return $this->sendResponse(200, 'User Berhasil ditambah', $user, 201);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user_id = User::findOrFail($id)->id;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = $user->foto;
            $oldFotoPath = base_path('public/images/foto/') . $filename;

            if ($user->foto && file_exists($oldFotoPath)) {
                unlink($oldFotoPath);
            }

            $foto->move(base_path('public/images/foto/'), $filename);

            $foto = $filename;
            $user->save();

            $url_foto = url_foto_frontend('images/foto/') . $filename;
        } else {
            $foto = $user->foto;
            $url_foto = $user->url_foto;
        }

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'status' => $request->status,
            'foto' => $foto,
            'url_foto' => $url_foto,
            'kewenangan_id' => $request->kewenangan_id,
        ]);

        $user->save();

        $logController = app(LogControllers::class);
        $logController->addToLog('Update Pengguna ' . $user_id);

        return $this->sendResponse(200, 'Pengguna ' . $user_id . ' diperbarui', $user, 201);
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            $faceRecognition = $user->faceRecognize();
            if ($faceRecognition) {
                $faceRecognition->delete();
            }

            $filePath = base_path('images/foto/') . $user->foto;
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $user->delete();

            $logController = app(LogControllers::class);
            $logController->addToLog('Hapus Pengguna ' . $user->id);

            return $this->messageResponse(200, 'Pengguna ' . $user->id . ' dihapus', 200);
        } catch (ModelNotFoundException $e) {
            return $this->messageResponse(404, 'Pengguna ' . $id . ' tidak ditemukan', 404);
        } catch (\Exception $e) {
            return $this->messageResponse(500, 'Error: ' . $e->getMessage(), 500);
        }
    }
}
