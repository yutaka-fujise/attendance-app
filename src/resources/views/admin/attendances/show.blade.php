@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-attendance-detail.css') }}">
@endsection

@section('content')
<div class="admin-attendance-detail">
    <div class="admin-attendance-detail__inner">
        <h2 class="admin-attendance-detail__heading">勤怠詳細</h2>
        @if ($errors->any())
    <div class="admin-attendance-detail__errors">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form action="{{ route('admin.attendances.update', $attendance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="admin-attendance-detail__table">
                <div class="admin-attendance-detail__row">
                    <div class="admin-attendance-detail__label">名前</div>
                    <div class="admin-attendance-detail__value">
                        {{ $attendance->user->name }}
                    </div>
                </div>

                <div class="admin-attendance-detail__row">
                    <div class="admin-attendance-detail__label">日付</div>
                    <div class="admin-attendance-detail__value admin-attendance-detail__value--date">
                        <span>{{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}</span>
                        <span>{{ \Carbon\Carbon::parse($attendance->date)->format('n月j日') }}</span>
                    </div>
                </div>

                <div class="admin-attendance-detail__row">
                    <div class="admin-attendance-detail__label">出勤・退勤</div>
                    <div class="admin-attendance-detail__value admin-attendance-detail__value--time-range">
                        <input
                            type="time"
                            name="clock_in"
                            value="{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}"
                            class="admin-attendance-detail__input admin-attendance-detail__input--time">
                        <span class="admin-attendance-detail__separator">〜</span>
                        <input
                            type="time"
                            name="clock_out"
                            value="{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}"
                            class="admin-attendance-detail__input admin-attendance-detail__input--time">
                    </div>
                </div>

                @php
                    $break1 = $attendance->breaks[0] ?? null;
                    $break2 = $attendance->breaks[1] ?? null;
                @endphp

                <div class="admin-attendance-detail__row">
                    <div class="admin-attendance-detail__label">休憩</div>
                    <div class="admin-attendance-detail__value admin-attendance-detail__value--time-range">
                        <input
                            type="time"
                            name="break1_start"
                            value="{{ $break1 && $break1->break_start ? \Carbon\Carbon::parse($break1->break_start)->format('H:i') : '' }}"
                            class="admin-attendance-detail__input admin-attendance-detail__input--time">
                        <span class="admin-attendance-detail__separator">〜</span>
                        <input
                            type="time"
                            name="break1_end"
                            value="{{ $break1 && $break1->break_end ? \Carbon\Carbon::parse($break1->break_end)->format('H:i') : '' }}"
                            class="admin-attendance-detail__input admin-attendance-detail__input--time">
                    </div>
                </div>

                <div class="admin-attendance-detail__row">
                    <div class="admin-attendance-detail__label">休憩2</div>
                    <div class="admin-attendance-detail__value admin-attendance-detail__value--time-range">
                        <input
                            type="time"
                            name="break2_start"
                            value="{{ $break2 && $break2->break_start ? \Carbon\Carbon::parse($break2->break_start)->format('H:i') : '' }}"
                            class="admin-attendance-detail__input admin-attendance-detail__input--time">
                        <span class="admin-attendance-detail__separator">〜</span>
                        <input
                            type="time"
                            name="break2_end"
                            value="{{ $break2 && $break2->break_end ? \Carbon\Carbon::parse($break2->break_end)->format('H:i') : '' }}"
                            class="admin-attendance-detail__input admin-attendance-detail__input--time">
                    </div>
                </div>

                <div class="admin-attendance-detail__row">
                    <div class="admin-attendance-detail__label">備考</div>
                    <div class="admin-attendance-detail__value">
                        <textarea
                            name="note"
                            class="admin-attendance-detail__textarea">{{ $attendance->note ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="admin-attendance-detail__button-wrap">
                <button type="submit" class="admin-attendance-detail__button">修正</button>
            </div>
        </form>
    </div>
</div>
@endsection