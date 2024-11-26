<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $fillable = [
        'nama_apk', 'footer', 'favico', 'logo_header', 'url_favico', 'url_logo_header'
    ];
}
