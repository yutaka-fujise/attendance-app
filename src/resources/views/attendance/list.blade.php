@extends('layouts.app')

@section('title', '勤怠一覧')

@section('content')
<div class="attendance-list-page">
    <div class="attendance-list-container">
        <h1 class="attendance-list-title">勤怠一覧</h1>

        <div class="attendance-list-month-nav">
            <div class="month-nav-prev">
                <a href="{{ route('attendance.list', ['month' => $now->copy()->subMonth()->format('Y-m')]) }}">
                    ← 前月
                </a>
            </div>

            <div class="month-nav-current">
                {{ $now->format('Y/m') }}
            </div>

            <div class="month-nav-next">
                <a href="{{ route('attendance.list', ['month' => $now->copy()->addMonth()->format('Y-m')]) }}">
                    翌月 →
                </a>
            </div>
        </div>

        <div class="attendance-list-table-wrap">
            <table class="attendance-list-table">
                <thead>
                    <tr>
                        <th>日付</th>
                        <th>出勤</th>
                        <th>退勤</th>
                        <th>休憩</th>
                        <th>合計</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($day = 1; $day <= $endOfMonth->day; $day++)
                        @php
                            $date = $startOfMonth->copy()->day($day)->toDateString();
                            $attendance = $attendances[$date] ?? null;
                            $currentDate = $startOfMonth->copy()->day($day);
                            $weekday = ['日', '月', '火', '水', '木', '金', '土'][$currentDate->dayOfWeek];
                        @endphp

                        <tr>
                            <td>
                                {{ $currentDate->format('m/d') }}({{ $weekday }})
                            </td>

                            <td>
                                {{ $attendance && $attendance->clock_in
                                    ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')
                                    : '' }}
                            </td>

                            <td>
                                {{ $attendance && $attendance->clock_out
                                    ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')
                                    : '' }}
                            </td>

                            <td>
                                @if ($attendance)
                                    @php
                                        $totalBreak = 0;

                                        foreach ($attendance->breaks as $break) {
                                            if ($break->break_start && $break->break_end) {
                                                $start = \Carbon\Carbon::parse($break->break_start);
                                                $end = \Carbon\Carbon::parse($break->break_end);
                                                $totalBreak += $end->diffInSeconds($start);
                                            }
                                        }

                                        $hours = floor($totalBreak / 3600);
                                        $minutes = floor(($totalBreak % 3600) / 60);
                                    @endphp

                                    {{ sprintf('%02d:%02d', $hours, $minutes) }}
                                @endif
                            </td>

                            <td>
                                @if ($attendance && $attendance->clock_in && $attendance->clock_out)
                                    @php
                                        $clockIn = \Carbon\Carbon::parse($attendance->clock_in);
                                        $clockOut = \Carbon\Carbon::parse($attendance->clock_out);

                                        $workSeconds = $clockOut->diffInSeconds($clockIn) - $totalBreak;

                                        $workHours = floor($workSeconds / 3600);
                                        $workMinutes = floor(($workSeconds % 3600) / 60);
                                    @endphp

                                    {{ sprintf('%02d:%02d', $workHours, $workMinutes) }}
                                @endif
                            </td>

                            <td>
                                @if ($attendance)
                                    <a href="{{ route('attendance.show', $attendance->id) }}">詳細</a>
                                @endif
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection