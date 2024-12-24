<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'nama', 'route', 'url', 'slug', 'icon', 'status', 'kewenangan_id', 'kategori','parent_id'
    ];
}
