@extends('layouts.admin')

@section('content')
<div class="admin-attendance-list">
    <div class="admin-attendance-list__inner">

        <h2 class="admin-attendance-list__heading">
            {{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}の勤怠
        </h2>

        <div class="admin-attendance-list__date-nav">
            <a
                href="{{ route('admin.attendances.index', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}"
                class="admin-attendance-list__date-link"
            >
                前日
            </a>

            <div class="admin-attendance-list__date-current">
                {{ \Carbon\Carbon::parse($date)->format('Y/m/d') }}
            </div>

            <a
                href="{{ route('admin.attendances.index', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}"
                class="admin-attendance-list__date-link"
            >
                翌日
            </a>
        </div>

        <div class="admin-attendance-list__table-wrap">
            <table class="admin-attendance-list__table">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>出勤</th>
                        <th>退勤</th>
                        <th>休憩</th>
                        <th>合計</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        @php
                            $breakMinutes = 0;
                            foreach ($attendance->breaks as $break) {
                                if ($break->break_start && $break->break_end) {
                                    $breakMinutes += \Carbon\Carbon::parse($break->break_start)
                                        ->diffInMinutes(\Carbon\Carbon::parse($break->break_end));
                                }
                            }

                            $workMinutes = 0;
                            if ($attendance->clock_in && $attendance->clock_out) {
                                $workMinutes = \Carbon\Carbon::parse($attendance->clock_in)
                                    ->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_out)) - $breakMinutes;
                            }
                        @endphp

                        <tr>
                            <td>{{ $attendance->user->name }}</td>

                            <td>
                                {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}
                            </td>

                            <td>
                                {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}
                            </td>

                            <td>
                                {{ sprintf('%d:%02d', floor($breakMinutes / 60), $breakMinutes % 60) }}
                            </td>

                            <td>
                                {{ sprintf('%d:%02d', floor($workMinutes / 60), $workMinutes % 60) }}
                            </td>

                            <td>
                                <a
                                    href="{{ route('admin.attendances.show', $attendance->id) }}"
                                    class="admin-attendance-list__detail-link">
                                    詳細
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection