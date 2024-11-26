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

    public function regisFR(Request $request, $id)
    {
        $fr = FaceRecognation::create([
            'id_user' => $id,
            'descriptors' => json_encode($request->input('descriptors')),
        ]);

        $user = User::find($id);
        if ($user) {
            $user->face_id = $fr->id;
            $user->save();
        }

        return $this->sendResponse(200, 'Registrasi Face Recognation Berhasil', $fr, 200);
    }
}
