<h1>勤怠詳細</h1>

<p>名前：{{ $attendance->user->name }}</p>
<p>日付：{{ \Carbon\Carbon::parse($attendance->date)->format('Y年m月d日') }}</p>
<p>出勤：{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}</p>
<p>退勤：{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}</p>


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

<p>休憩合計：{{ sprintf('%02d:%02d', $hours, $minutes) }}</p>

@php
    if ($attendance->clock_in && $attendance->clock_out) {
        $workSeconds =
            \Carbon\Carbon::parse($attendance->clock_out)
            ->diffInSeconds(\Carbon\Carbon::parse($attendance->clock_in))
            - $totalBreak;

        $workHours = floor($workSeconds / 3600);
        $workMinutes = floor(($workSeconds % 3600) / 60);
    }
@endphp

<p>
勤務時間：
{{ isset($workHours) ? sprintf('%02d:%02d', $workHours, $workMinutes) : '' }}
</p>
<p>備考：{{ $attendance->note ?? '' }}</p>
<h2>休憩</h2>

<table border="1">
    <tr>
        <th>休憩開始</th>
        <th>休憩終了</th>
    </tr>

    @foreach ($attendance->breaks as $break)
        <tr>
            <td>{{ $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}</td>
            <td>{{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}</td>
        </tr>
    @endforeach
</table>