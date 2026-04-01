<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceCorrection extends Model
{
    protected $fillable = [
        'attendance_id',
        'user_id',
        'clock_in',
        'clock_out',
        'note',
        'status',
    ];
    public function breaks()
{
    return $this->hasMany(CorrectionBreak::class);
}
}
