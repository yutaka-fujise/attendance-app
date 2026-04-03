<?php

namespace App\Http\Controllers;

use App\Models\AttendanceCorrection;
use Illuminate\Support\Facades\Auth;

class AttendanceCorrectionController extends Controller
{
    public function index()
    {
        $corrections = AttendanceCorrection::with('attendance')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('attendance.corrections.index', compact('corrections'));
    }

    public function show(AttendanceCorrection $correction)
    {
        if ($correction->user_id !== Auth::id()) {
            abort(403);
        }

        $correction->load('attendance', 'correctionBreaks');

        return view('attendance.corrections.show', compact('correction'));
    }
}