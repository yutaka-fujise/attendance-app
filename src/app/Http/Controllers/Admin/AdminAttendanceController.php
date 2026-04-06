<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $attendances = Attendance::with(['user', 'breaks'])
            ->whereDate('date', $date)
            ->get();

        return view('admin.attendances.index', compact('attendances', 'date'));
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('user', 'breaks');

        return view('admin.attendances.show', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        // 勤怠更新
        $attendance->update([
            'clock_in'  => $request->clock_in,
            'clock_out' => $request->clock_out,
            'note'      => $request->note,
        ]);

        // 休憩更新（とりあえず2つ想定）
        $breaks = $attendance->breaks;

        if (isset($breaks[0])) {
            $breaks[0]->update([
                'break_start' => $request->break1_start,
                'break_end'   => $request->break1_end,
            ]);
        }

        if (isset($breaks[1])) {
            $breaks[1]->update([
                'break_start' => $request->break2_start,
                'break_end'   => $request->break2_end,
            ]);
        }

        return redirect()->route('admin.attendances.index', [
            'date' => $attendance->date,
        ])->with('success', '勤怠を更新しました');
    }
}