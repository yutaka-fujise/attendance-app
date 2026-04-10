@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-staff-attendance-list.css') }}">
@endsection

@section('content')

@php
    $month = \Carbon\Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();
@endphp

<div class="admin-staff-attendance-list">
    <div class="admin-staff-attendance-list__inner">
        <h2 class="admin-staff-attendance-list__heading">
            {{ $user->name }}さんの勤怠
        </h2>

        <div class="admin-staff-attendance-list__month-nav">
            <a
                href="{{ route('admin.staff.attendances', ['user' => $user->id, 'month' => $month->copy()->subMonth()->format('Y-m')]) }}"
                class="admin-staff-attendance-list__month-link"
            >
                前月
            </a>

            <form
                method="GET"
                action="{{ route('admin.staff.attendances', ['user' => $user->id]) }}"
                class="admin-staff-attendance-list__month-picker-form"
            >
                <button
                    type="button"
                    class="admin-staff-attendance-list__month-picker-button"
                    onclick="document.getElementById('staff-month-picker').showPicker ? document.getElementById('staff-month-picker').showPicker() : document.getElementById('staff-month-picker').click()"
                >
                    <span class="admin-staff-attendance-list__month-icon">📅</span>
                    <span>{{ $month->format('Y/m') }}</span>
                </button>

                <input
                    type="month"
                    id="staff-month-picker"
                    name="month"
                    value="{{ $month->format('Y-m') }}"
                    class="admin-staff-attendance-list__month-input-hidden"
                    onchange="this.form.submit()"
                >
            </form>

            <a
                href="{{ route('admin.staff.attendances', ['user' => $user->id, 'month' => $month->copy()->addMonth()->format('Y-m')]) }}"
                class="admin-staff-attendance-list__month-link"
            >
                翌月
            </a>
        </div>

        <div class="admin-staff-attendance-list__table-wrap">
            <table class="admin-staff-attendance-list__table">
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
                    @foreach ($days as $day)
                        @php
                            $attendance = $day['attendance'];
                            $breakMinutes = 0;
                            $workMinutes = 0;

                            if ($attendance) {
                                foreach ($attendance->breaks as $break) {
                                    if ($break->break_start && $break->break_end) {
                                        $breakMinutes += \Carbon\Carbon::parse($break->break_start)
                                            ->diffInMinutes(\Carbon\Carbon::parse($break->break_end));
                                    }
                                }

                                if ($attendance->clock_in && $attendance->clock_out) {
                                    $workMinutes = \Carbon\Carbon::parse($attendance->clock_in)
                                        ->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_out)) - $breakMinutes;
                                }
                            }
                        @endphp

                        <tr>
                            <td>{{ $day['date']->translatedFormat('m/d(D)') }}</td>
                            <td>{{ $attendance && $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}</td>
                            <td>{{ $attendance && $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}</td>
                            <td>{{ $attendance ? sprintf('%d:%02d', floor($breakMinutes / 60), $breakMinutes % 60) : '' }}</td>
                            <td>{{ $attendance ? sprintf('%d:%02d', floor($workMinutes / 60), $workMinutes % 60) : '' }}</td>
                            <td>
                                @if ($attendance)
                                    <a
                                        href="{{ route('admin.attendances.show', $attendance->id) }}"
                                        class="admin-staff-attendance-list__detail-link"
                                    >
                                        詳細
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="admin-staff-attendance-list__csv-wrap">
            <a
                href="{{ route('admin.staff.attendances.csv', ['user' => $user->id, 'month' => $currentMonth]) }}"
                class="admin-staff-attendance-list__csv-button"
            >
                CSV出力
            </a>
        </div>
    </div>
</div>
@endsection