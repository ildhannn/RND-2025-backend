<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaceRecognation extends Model
{
    protected $fillable = ['id_user', 'descriptors'];

    public function user()
    {
        return $this->belongsTo(User::class, 'face_id', 'id');
    }
}
