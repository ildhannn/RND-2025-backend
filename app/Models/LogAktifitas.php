<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktifitas extends Model
{
    protected $fillable = [
        'nama_aktifitas', 'ip', 'browser', 'user'
    ];
}
