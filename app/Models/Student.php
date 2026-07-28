<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    protected $appends = ['photo_url'];

    protected function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('public/storage/' . $this->photo) : asset('placeholder.jpg');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }


    protected $casts = [
        'face_embedding' => 'array',
    ];

}
