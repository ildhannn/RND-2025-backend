<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Spatie\Image\Image;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function getPengaturan()
    {
        $pengaturan = Pengaturan::get();
        return $this->sendResponse(200, 'Pengaturan Saat ini', $pengaturan, 200);
    }

    public function updatePengaturan(Request $request)
    {
        $pengaturan = Pengaturan::findOrFail(1);

        if ($request->nama_apk || $request->footer) {
            $filenamefavico = 'favico' . '.ico';
            $url_favico = url_foto_frontend('images/setting/') . $filenamefavico;

            $logo_header = $pengaturan->logo_header;
            $filenameheader = 'logo_header' . '.png';
            $url_header = url_foto_frontend('images/setting/') . $filenameheader;

            $pengaturan->update([
                'nama_apk' => $request->nama_apk,
                'footer' => $request->footer,
                'favico' => $filenamefavico,
                'url_favico' => $url_favico,
                'logo_header' => $filenameheader,
                'url_logo_header' => $url_header,
            ]);

            $pengaturan->save();
        } else {
            $nama_apk = $pengaturan->nama_apk;
            $footer = $pengaturan->footer;

            $pengaturan->update([
                'nama_apk' => $nama_apk,
                'footer' => $footer,
            ]);

            $pengaturan->save();
        }

        if ($request->hasFile('favico')) {
            $favico = $request->favico;
            // $filenamefavico = 'favico_' . $pengaturan->nama_apk . '.' . $favico->getClientOriginalExtension();
            $filenamefavico = 'favico' . '.ico';
            $filePath = base_path('public/images/setting/') . $filenamefavico;

            Image::load($favico)->optimize()->width(50)->height(50)->save($filePath);

            if ($request->favico && file_exists($filePath)) {
                unlink($filePath);
            }

            $favico->move(base_path('public/images/setting/'), $filenamefavico);
            $favico = $filenamefavico;
            $url_favico = url_foto_frontend('images/setting/') . $filenamefavico;

            $pengaturan->update([
                'favico' => $favico,
                'url_favico' => $url_favico,
            ]);

            $pengaturan->save();
        } else {
            $favico = $pengaturan->favico;
            $url_favico = $pengaturan->url_favico;

            $pengaturan->update([
                'favico' => $favico,
                'url_favico' => $url_favico,
            ]);

            $pengaturan->save();
        }

        if ($request->hasFile('logo_header')) {
            $logo_header = $request->logo_header;
            $filenameheader = 'logo_header' . '.' . $logo_header->getClientOriginalExtension();
            $filePath = base_path('public/images/setting/') . $filenameheader;

            if ($request->logo_header && file_exists($filePath)) {
                unlink($filePath);
            }

            $logo_header->move(base_path('public/images/setting/'), $filenameheader);
            $logo_header = $filenameheader;
            $url_logo_header = url_foto_frontend('images/setting/') . $filenameheader;

            $pengaturan->update([
                'logo_header' => $logo_header,
                'url_logo_header' => $url_logo_header,
            ]);

            $pengaturan->save();
        } else {
            $logo_header = $pengaturan->logo_header;
            $url_logo_header = $pengaturan->url_logo_header;

            $pengaturan->update([
                'logo_header' => $logo_header,
                'url_logo_header' => $url_logo_header,
            ]);

            $pengaturan->save();
        }

        $logController = app(LogControllers::class);
        $logController->addToLog('Update Pengaturan');

        return $this->sendResponse(200, 'Pengaturan diupdate', $pengaturan, 201);
    }
}
