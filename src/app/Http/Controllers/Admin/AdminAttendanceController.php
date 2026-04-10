<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceUpdateRequest;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->filled('date')
            ? Carbon::createFromFormat('Y-m-d', $request->date)->toDateString()
            : Carbon::today()->toDateString();

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

    public function update(AttendanceUpdateRequest $request, Attendance $attendance)
    {
        $attendance->update([
            'clock_in'  => $request->clock_in,
            'clock_out' => $request->clock_out,
            'note'      => $request->note,
        ]);

        $breaks = $attendance->breaks()->orderBy('id')->get();

        $breakData = [
            [
                'start' => $request->break1_start,
                'end'   => $request->break1_end,
            ],
            [
                'start' => $request->break2_start,
                'end'   => $request->break2_end,
            ],
        ];

        foreach ($breakData as $index => $data) {
            $existingBreak = $breaks[$index] ?? null;

            $hasStart = !empty($data['start']);
            $hasEnd   = !empty($data['end']);

            if ($hasStart && $hasEnd) {
                if ($existingBreak) {
                    $existingBreak->update([
                        'break_start' => $data['start'],
                        'break_end'   => $data['end'],
                    ]);
                } else {
                    $attendance->breaks()->create([
                        'break_start' => $data['start'],
                        'break_end'   => $data['end'],
                    ]);
                }
            }

            if (!$hasStart && !$hasEnd) {
                if ($existingBreak) {
                    $existingBreak->delete();
                }
            }
        }

        return redirect()->route('admin.attendances.index', [
            'date' => $attendance->date,
        ])->with('success', '勤怠を更新しました');
    }

    public function staffAttendances(Request $request, User $user)
    {
        $currentMonth = $request->input('month', now()->format('Y-m'));
        $month = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();

        $attendances = Attendance::with('breaks')
            ->where('user_id', $user->id)
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->orderBy('date')
            ->get()
            ->keyBy(function ($attendance) {
                return Carbon::parse($attendance->date)->toDateString();
            });

        $days = [];
        for ($date = $month->copy()->startOfMonth(); $date->lte($month->copy()->endOfMonth()); $date->addDay()) {
            $days[] = [
                'date'       => $date->copy(),
                'attendance' => $attendances->get($date->toDateString()),
            ];
        }

        return view('admin.attendances.staff', compact('user', 'currentMonth', 'days'));
    }

    public function exportCsv(Request $request, User $user)
    {
        $currentMonth = $request->input('month', now()->format('Y-m'));
        $month = Carbon::parse($currentMonth . '-01');

        $attendances = Attendance::with('breaks')
            ->where('user_id', $user->id)
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->orderBy('date')
            ->get();

        $fileName = $user->name . '_' . $month->format('Y_m') . '_attendances.csv';

        $response = new StreamedResponse(function () use ($attendances) {
            $handle = fopen('php://output', 'w');

            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['日付', '出勤', '退勤', '休憩', '合計']);

            foreach ($attendances as $attendance) {
                $breakMinutes = 0;
                foreach ($attendance->breaks as $break) {
                    if ($break->break_start && $break->break_end) {
                        $breakMinutes += Carbon::parse($break->break_start)
                            ->diffInMinutes(Carbon::parse($break->break_end));
                    }
                }

                $workMinutes = 0;
                if ($attendance->clock_in && $attendance->clock_out) {
                    $workMinutes = Carbon::parse($attendance->clock_in)
                        ->diffInMinutes(Carbon::parse($attendance->clock_out)) - $breakMinutes;
                }

                fputcsv($handle, [
                    Carbon::parse($attendance->date)->format('m/d'),
                    $attendance->clock_in ? Carbon::parse($attendance->clock_in)->format('H:i') : '',
                    $attendance->clock_out ? Carbon::parse($attendance->clock_out)->format('H:i') : '',
                    sprintf('%d:%02d', floor($breakMinutes / 60), $breakMinutes % 60),
                    sprintf('%d:%02d', floor($workMinutes / 60), $workMinutes % 60),
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}