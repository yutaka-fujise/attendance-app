<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectionBreak extends Model
{
    protected $fillable = [
        'attendance_correction_id',
        'break_start',
        'break_end',
    ];
}