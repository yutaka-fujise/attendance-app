@extends('layouts.admin')

@section('title', '勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-attendance-list.css') }}">
@endsection

@section('content')
<div class="admin-attendance-list-page">
    <div class="admin-attendance-list-container">

        <h1 class="admin-attendance-list-title">
            {{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}の勤怠
        </h1>

        <div class="admin-attendance-date-nav">
            <a
                href="{{ route('admin.attendances.index', ['date' => \Carbon\Carbon::parse($date)->copy()->subDay()->toDateString()]) }}"
                class="admin-attendance-date-nav__link admin-attendance-date-nav__link--prev"
            >
                <span class="admin-attendance-date-nav__arrow">←</span>
                <span>前日</span>
            </a>

            <form method="GET" action="{{ route('admin.attendances.index') }}" class="admin-attendance-date-picker-form">
                <button
                    type="button"
                    class="admin-attendance-date-picker-button"
                    onclick="document.getElementById('admin-date-picker').showPicker ? document.getElementById('admin-date-picker').showPicker() : document.getElementById('admin-date-picker').click()"
                >
                    <span class="admin-attendance-date-picker-button__icon">📅</span>
                    <span class="admin-attendance-date-picker-button__text">
                        {{ \Carbon\Carbon::parse($date)->format('Y/m/d') }}
                    </span>
                </button>

                <input
                    type="date"
                    id="admin-date-picker"
                    name="date"
                    value="{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}"
                    class="admin-attendance-date-picker-input-hidden"
                    onchange="this.form.submit()"
                >
            </form>

            <a
                href="{{ route('admin.attendances.index', ['date' => \Carbon\Carbon::parse($date)->copy()->addDay()->toDateString()]) }}"
                class="admin-attendance-date-nav__link admin-attendance-date-nav__link--next"
            >
                <span>翌日</span>
                <span class="admin-attendance-date-nav__arrow">→</span>
            </a>
        </div>

        <div class="admin-attendance-table-wrap">
            <table class="admin-attendance-table">
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
                            <td>{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}</td>
                            <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}</td>
                            <td>{{ sprintf('%d:%02d', floor($breakMinutes / 60), $breakMinutes % 60) }}</td>
                            <td>{{ sprintf('%d:%02d', floor($workMinutes / 60), $workMinutes % 60) }}</td>
                            <td>
                                <a
                                    href="{{ route('admin.attendances.show', $attendance->id) }}"
                                    class="admin-attendance-table__detail-link"
                                >
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