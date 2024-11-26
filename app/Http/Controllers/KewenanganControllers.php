<?php

namespace App\Http\Controllers;

use App\Models\Kewenangan;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KewenanganControllers extends Controller
{
    public function getAllKewenangan()
    {
        $kewenangan = Kewenangan::get();
        return $this->sendResponse(200, 'List Kewenangan', $kewenangan, 200);
    }

    public function getKewenanganMenu($id)
    {
        $kewenangan = Kewenangan::findOrFail($id);
        $menu = Menu::where('status', 1)
            ->where('kewenangan_id', $kewenangan)
            ->get();

        return $this->sendResponse(200, 'Menu Kewenangan ' . $id, $menu, 200);
    }

    public function createKewenangan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors());
        }

        $kewenangan = Kewenangan::create([
            'nama' => $request->nama,
            'status' => $request->status,
        ]);

        return $this->sendResponse(200, 'Kewenangan ditambahkan', $kewenangan, 201);
    }
}
