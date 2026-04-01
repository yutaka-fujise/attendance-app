<h1>{{ $now->format('Y年m月') }} の勤怠一覧</h1>

<table border="1">
    <tr>
    <th>日付</th>
    <th>出勤</th>
    <th>退勤</th>
    <th>休憩</th>
    <th>勤務時間</th>
</tr>

    @for ($day = 1; $day <= $endOfMonth->day; $day++)
        @php
            $date = $startOfMonth->copy()->day($day)->toDateString();
            $attendance = $attendances[$date] ?? null;
        @endphp

        <tr>
            <td>
    @if ($attendance)
        <a href="{{ route('attendance.show', $attendance->id) }}">{{ $day }}日</a>
    @else
        {{ $day }}日
    @endif
</td>

            <td>
    {{ $attendance && $attendance->clock_in 
        ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') 
        : '' 
    }}
</td>

<td>
    {{ $attendance && $attendance->clock_out 
        ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') 
        : '' 
    }}
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
        </tr>
    @endfor
</table>