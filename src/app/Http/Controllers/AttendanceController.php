<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\BreakTime;
use App\Models\CorrectionBreak;
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

    public function list()
    {
        $user = auth()->user();
        $now  = Carbon::now();

        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth   = $now->copy()->endOfMonth();

        $attendances = Attendance::with('breaks')
            ->where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy('date');

        return view('attendance.list', compact(
            'now',
            'startOfMonth',
            'endOfMonth',
            'attendances'
        ));
    }

    public function show(Attendance $attendance)
{
    if ($attendance->user_id !== auth()->id()) {
        abort(403);
    }

    $attendance->load(['breaks', 'user', 'corrections']);

    $latestCorrection = $attendance->corrections()
        ->where('user_id', auth()->id())
        ->latest()
        ->first();

    return view('attendance.show', compact('attendance', 'latestCorrection'));
}

    public function update(Request $request, Attendance $attendance)
    {
        if ($attendance->user_id !== auth()->id()) {
            abort(403);
        }

        $hasCorrection = $attendance->corrections()
            ->where('user_id', auth()->id())
            ->exists();

        if ($hasCorrection) {
            return redirect()
                ->route('attendance.show', $attendance->id)
                ->with('message', '修正申請済みのため修正はできません。');
        }

        $request->validate([
            'clock_in'  => ['required'],
            'clock_out' => ['required', 'after:clock_in'],
            'note'      => ['required'],
        ]);

        $correction = AttendanceCorrection::create([
            'attendance_id' => $attendance->id,
            'user_id'       => auth()->id(),
            'clock_in'      => $request->clock_in,
            'clock_out'     => $request->clock_out,
            'note'          => $request->note,
            'status'        => 'pending',
        ]);

        if ($request->has('breaks')) {
            foreach ($request->breaks as $break) {
                if (empty($break['break_start']) && empty($break['break_end'])) {
                    continue;
                }

                CorrectionBreak::create([
                    'attendance_correction_id' => $correction->id,
                    'break_start'              => $break['break_start'] ?? null,
                    'break_end'                => $break['break_end'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('attendance.show', $attendance->id)
            ->with('message', '修正申請を送信しました');
    }
}