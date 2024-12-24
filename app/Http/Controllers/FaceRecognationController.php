<?php

namespace App\Http\Controllers;

use App\Models\FaceRecognation;
use App\Models\User;
use Illuminate\Http\Request;

class FaceRecognationController extends Controller
{
    public function getFR($id)
    {
        $user = User::findOrFail($id);
        // $fr = FaceRecognation::where('id_user', $user)->get();
        $fr = $user->faceRecognize;

        return $this->sendResponse(200, 'Face Recognation ' . $id, [$fr], 200);
    }

    // face api
    // public function regisFR(Request $request, $id)
    // {
    //     $fr = FaceRecognation::create([
    //         'id_user' => $id,
    //         'descriptors' => json_encode($request->input('descriptors')),
    //     ]);

    //     $user = User::find($id);

    //     if ($user) {
    //         $user->face_id = $fr->id;
    //         $user->save();
    //     }

    //     return $this->sendResponse(200, 'Registrasi Face Recognation Berhasil', $fr, 200);
    // }

    // exadle
    public function regisFR($id)
    {
        $fr = FaceRecognation::create([
            'id_user' => $id,
            'status' => 1
        ]);

        $user = User::find($id);

        if ($user) {
            $user->face_id = $fr->id;
            $user->save();
        }

        return $this->sendResponse(200, 'Registrasi Face Recognation Berhasil', $fr, 200);
    }

    public function updateFR(Request $request, $id) {
        $id_fr = FaceRecognation::findOrFail($id);

        $id_fr->update([
            'status' => $request->input('status')
        ]);

        $id_fr->save();

        return $this->sendResponse(200, 'Update Status Face Recognation Berhasil', $id_fr, 200);

    }
}
