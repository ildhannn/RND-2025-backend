<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktifitas extends Model
{
    protected $fillable = [
        'nama_aktifitas', 'user', 'ip_private', 'ip_public', 'host', 'browser','bot','inapp', 'user_agent'
    ];
}
