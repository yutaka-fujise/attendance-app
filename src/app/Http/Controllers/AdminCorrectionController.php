<?php

namespace App\Http\Controllers;

use App\Models\AttendanceCorrection;

class AdminCorrectionController extends Controller
{
    public function index()
    {
        $pendingCorrections = AttendanceCorrection::with(['user', 'attendance'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedCorrections = AttendanceCorrection::with(['user', 'attendance'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.corrections.index', compact(
            'pendingCorrections',
            'approvedCorrections'
        ));
    }

    public function show(AttendanceCorrection $correction)
    {
        $correction->load(['user', 'attendance', 'correctionBreaks']);

        return view('admin.corrections.show', compact('correction'));
    }

    public function approve(AttendanceCorrection $correction)
    {
        $correction->load('attendance', 'correctionBreaks');

        $attendance = $correction->attendance;

        $attendance->clock_in  = $correction->clock_in;
        $attendance->clock_out = $correction->clock_out;
        $attendance->note      = $correction->note;
        $attendance->save();

        $attendance->breaks()->delete();

        foreach ($correction->correctionBreaks as $break) {
            $attendance->breaks()->create([
                'break_start' => $break->break_start,
                'break_end'   => $break->break_end,
            ]);
        }

        $correction->status = 'approved';
        $correction->save();

        return redirect()
            ->route('admin.corrections.index')
            ->with('success', '申請を承認しました');
    }
}