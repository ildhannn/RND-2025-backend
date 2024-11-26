<?php

namespace App\Http\Controllers;

use App\Models\LogAktifitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class LogControllers extends Controller
{
    public function getAllLog()
    {
        $log = LogAktifitas::get();
        return $this->sendResponse('success', 'List Log', $log, 200);
    }

    public function addToLog($subject)
    {
        $agent = new Agent();

        $ip = request()->ips();
        $browser = $agent->platform();

        $user = User::findOrFail(Auth::user()->id)->username;

        LogAktifitas::create([
            'nama_aktifitas' => $subject,
            'ip' => $ip[0],
            'browser' => $browser,
            'user' => $user,
        ]);
    }
}
