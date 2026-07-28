<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = [];
    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    protected $casts = [
        'face_embedding' => 'array',
    ];
}
