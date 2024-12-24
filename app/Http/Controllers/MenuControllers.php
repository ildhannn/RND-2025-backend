<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuControllers extends Controller
{
    public function getAllMenu()
    {
        $menu['total'] = Menu::get()->count();
        $menu['aktif'] = Menu::where('status', 1)->get()->count();
        $menu['tidak_aktif'] = Menu::where('status', 0)->get()->count();
        $menu['dashboard'] = Menu::where('kategori', 'dashboard')->get();
        $menu['users'] = Menu::where('kategori', 'user')->get();
        $menu['log'] = Menu::where('kategori', 'log')->get();
        $menu['menus'] = Menu::where('kategori', 'menu')->get();
        $menu['pengaturan'] = Menu::where('kategori', 'pengaturan')->get();

        $logController = app(LogControllers::class);
        $logController->addToLog('Menampilkan list seluruh menu');

        return $this->sendResponse(200, 'List Semua Menu', $menu, 200);
    }

    public function getParentMenu()
    {
        $parent = Menu::where('parent_id', '=', null)->orWhere('status', '=', 1)->get();

        return $this->sendResponse(200, 'List Parent Menu', $parent, 200);
    }

    public function getMenuUser()
    {
        $kewenangan_user = User::findOrFail(Auth::user()->id)->kewenangan_id;
        $user = User::findOrFail(Auth::user()->id)->username;

        $menuItems = Menu::where('status', 1)
            ->where(function ($query) use ($kewenangan_user) {
                $query
                    ->where('kewenangan_id', 'LIKE', '%"' . $kewenangan_user . '"%') // multiple kewenangan
                    ->orWhere('kewenangan_id', '=', $kewenangan_user); //single kewenangan
            })
            ->get();

        $menuTree = [];
        foreach ($menuItems as $menu) {
            if ($menu->parent_id === null) {
                $menuTree[$menu->id] = [
                    'nama' => $menu->nama,
                    'slug' => $menu->slug,
                    'icon' => $menu->icon,
                    'status' => $menu->status,
                    'kewenangan_id' => $menu->kewenangan_id,
                    'kategori' => $menu->kategori,
                ];
            } else {
                $menuTree[$menu->parent_id]['submenu'][] = [
                    'url' => $menu->url,
                    'nama' => $menu->nama,
                    'slug' => $menu->slug,
                    'icon' => $menu->icon,
                    'status' => $menu->status,
                    'kewenangan_id' => $menu->kewenangan_id,
                    'kategori' => $menu->kategori,
                ];
            }
        }

        $result = array_values($menuTree);

        $logController = app(LogControllers::class);
        $logController->addToLog('Menampilkan list menu ' . $user);
        return $this->sendResponse(200, 'List Menu ' . $user, $result, 200);
    }

    public function getMenuId($id)
    {
        $menu = Menu::findOrFail($id);
        $nama_menu = Menu::findOrFail($id)->nama;
        return $this->sendResponse(200, 'Menu ' . $nama_menu . ' ditemenukan', $menu, 200);
    }

    public function createMenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'slug' => 'required|string',
            'url' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error.', $validator->errors());
        }

        $menu = Menu::create([
            'nama' => $request->nama,
            'slug' => $request->slug,
            'url' => $request->url,
            'kategori' => $request->kategori,
            'parent_id' => $request->parent_id,
            'status' => $request->status == '' ? 1 : $request->status,
            'kewenangan_id' => $request->kewenangan_id == '' ? json_encode([1]) : json_encode($request->kewenangan_id),
        ]);

        $logController = app(LogControllers::class);
        $logController->addToLog('Menambahkan Menu');

        return $this->sendResponse(200, 'Menu ditambahkan', $menu, 201);
    }

    public function updateMenu(Request $request, $id)
    {
        // per-user
        // $menu = Menu::findOrFail(Auth::user()->id);
        // $user = Menu::findOrFail(Auth::user()->id)->nama;

        // all
        $menu = Menu::findOrFail($id);
        $user = Menu::findOrFail($id)->nama;

        $menu->update([
            'nama' => $request->nama,
            'slug' => $request->slug,
            'url' => $request->url,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'parent_id' => $request->parent_id,
            'kewenangan_id' => json_encode($request->kewenangan_id),
        ]);

        $logController = app(LogControllers::class);
        $logController->addToLog('Update menu');

        return $this->sendResponse(200, 'Menu ' . $user . ' diupdate', $menu, 200);
    }

    public function deleteMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $nama_menu = Menu::findOrFail($id)->nama;
        try {
            $menu->delete();
            $logController = app(LogControllers::class);
            $logController->addToLog('Hapus Menu ' . $nama_menu);
            return $this->messageResponse('success', 'Menu ' . $nama_menu . ' dihapus', 200);
        } catch (ModelNotFoundException $e) {
            return $this->messageResponse('error', 'Menu ' . $nama_menu . ' tidak ditemukan', 404);
        }
    }
}
