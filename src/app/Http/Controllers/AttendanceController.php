<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $now  = Carbon::now();
        $user = auth()->user();

        $attendance = Attendance::with('breaks')
            ->where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        $status = '勤務外';

        if ($attendance) {
            $latestBreak = $attendance->breaks->last();

            if ($attendance->clock_out) {
                $status = '退勤済';
            } elseif ($latestBreak && !$latestBreak->break_end) {
                $status = '休憩中';
            } elseif ($attendance->clock_in) {
                $status = '出勤中';
            }
        }

        return view('attendance.index', [
            'date'   => $now->format('Y年m月d日'),
            'time'   => $now->format('H:i:s'),
            'status' => $status,
        ]);
    }

    public function clockIn()
    {
        $now  = Carbon::now();
        $user = auth()->user();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if (!$attendance) {
            Attendance::create([
                'user_id'  => $user->id,
                'date'     => $now->toDateString(),
                'clock_in' => $now->format('H:i:s'),
            ]);
        }

        return redirect()->route('attendance.index');
    }

    public function breakStart()
    {
        $now  = Carbon::now();
        $user = auth()->user();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if ($attendance) {
            $latestBreak = $attendance->breaks()->latest()->first();

            if (!$latestBreak || $latestBreak->break_end) {
                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_start'   => $now->format('H:i:s'),
                ]);
            }
        }

        return redirect()->route('attendance.index');
    }

    public function breakEnd()
    {
        $now  = Carbon::now();
        $user = auth()->user();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if ($attendance) {
            $latestBreak = $attendance->breaks()
                ->whereNull('break_end')
                ->latest()
                ->first();

            if ($latestBreak) {
                $latestBreak->update([
                    'break_end' => $now->format('H:i:s'),
                ]);
            }
        }

        return redirect()->route('attendance.index');
    }

    public function clockOut()
    {
        $now  = Carbon::now();
        $user = auth()->user();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if ($attendance && !$attendance->clock_out) {
            $attendance->update([
                'clock_out' => $now->format('H:i:s'),
            ]);

            return redirect()
                ->route('attendance.index')
                ->with('message', 'お疲れ様でした。');
        }

        return redirect()->route('attendance.index');
    }
}