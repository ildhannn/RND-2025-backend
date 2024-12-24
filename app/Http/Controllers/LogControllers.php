<?php

namespace App\Http\Controllers;

use App\Models\LogAktifitas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;
use hisorange\BrowserDetect\Parser as Browser;

class LogControllers extends Controller
{
    public function getAllLog()
    {
        $log = LogAktifitas::get();

        // $userAgent = Request::getPort();
        // dd($userAgent);

        return $this->sendResponse('success', 'List Log', $log, 200);
    }

    public function addToLog($title)
    {
        $ip_private = request()->ips();
        $ip_public = file_get_contents('https://api.ipify.org');
        $browser = Browser::browserName();
        $device = Browser::deviceType();
        $inapp = Browser::isInApp();
        $userAgent = Browser::userAgent();
        $host = Request::getHost();
        $port = Request::getPort();

        $user = User::findOrFail(Auth::user()->id)->username;

        LogAktifitas::create([
            'nama_aktifitas' => $title,
            'user' => $user,
            'ip_private' => $ip_private[0],
            'ip_public' => $ip_public,
            'host' => $host . ':' . $port,
            'browser' => $browser,
            'bot' => $device,
            'inapp' => $inapp,
            'user_agent' => $userAgent,
        ]);
    }

    public function dashboard()
    {
        $log['login'] = LogAktifitas::where('nama_aktifitas', 'LIKE', '%' . 'Login' . '%')->get();
        $log['update_pengguna'] = LogAktifitas::where('nama_aktifitas', 'LIKE', '%' . 'Update Pengguna' . '%')->get();
        $log['update_menu'] = LogAktifitas::where('nama_aktifitas', 'LIKE', '%' . 'Update menu' . '%')->get();

        return $this->sendResponse(200, 'List User', $log, 200);
    }
}
