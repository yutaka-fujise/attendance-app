@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-attendance-detail.css') }}">
@endsection

@section('content')
@php
    $break1 = $attendance->breaks[0] ?? null;
    $break2 = $attendance->breaks[1] ?? null;
@endphp

<div class="admin-attendance-detail">
    <div class="admin-attendance-detail__inner">
        <h2 class="admin-attendance-detail__heading">勤怠詳細</h2>

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
                    <div class="admin-attendance-detail__value admin-attendance-detail__value--time-group">
                        <div class="admin-attendance-detail__time-range">
                            <input
                                type="time"
                                name="clock_in"
                                value="{{ old('clock_in', $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '') }}"
                                class="admin-attendance-detail__input admin-attendance-detail__input--time"
                            >
                            <span class="admin-attendance-detail__separator">〜</span>
                            <input
                                type="time"
                                name="clock_out"
                                value="{{ old('clock_out', $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '') }}"
                                class="admin-attendance-detail__input admin-attendance-detail__input--time"
                            >
                        </div>

                        @error('clock_in')
                            <p class="admin-attendance-detail__error">{{ $message }}</p>
                        @enderror

                        @error('clock_out')
                            <p class="admin-attendance-detail__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="admin-attendance-detail__row">
                    <div class="admin-attendance-detail__label">休憩</div>
                    <div class="admin-attendance-detail__value admin-attendance-detail__value--time-group">
                        <div class="admin-attendance-detail__time-range">
                            <input
                                type="time"
                                name="breaks[0][break_start]"
                                value="{{ old('breaks.0.break_start', $break1 && $break1->break_start ? \Carbon\Carbon::parse($break1->break_start)->format('H:i') : '') }}"
                                class="admin-attendance-detail__input admin-attendance-detail__input--time"
                            >
                            <span class="admin-attendance-detail__separator">〜</span>
                            <input
                                type="time"
                                name="breaks[0][break_end]"
                                value="{{ old('breaks.0.break_end', $break1 && $break1->break_end ? \Carbon\Carbon::parse($break1->break_end)->format('H:i') : '') }}"
                                class="admin-attendance-detail__input admin-attendance-detail__input--time"
                            >
                        </div>

                        @error('breaks.0.break_start')
                            <p class="admin-attendance-detail__error">{{ $message }}</p>
                        @enderror

                        @error('breaks.0.break_end')
                            <p class="admin-attendance-detail__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="admin-attendance-detail__row">
                    <div class="admin-attendance-detail__label">休憩2</div>
                    <div class="admin-attendance-detail__value admin-attendance-detail__value--time-group">
                        <div class="admin-attendance-detail__time-range">
                            <input
                                type="time"
                                name="breaks[1][break_start]"
                                value="{{ old('breaks.1.break_start', $break2 && $break2->break_start ? \Carbon\Carbon::parse($break2->break_start)->format('H:i') : '') }}"
                                class="admin-attendance-detail__input admin-attendance-detail__input--time"
                            >
                            <span class="admin-attendance-detail__separator">〜</span>
                            <input
                                type="time"
                                name="breaks[1][break_end]"
                                value="{{ old('breaks.1.break_end', $break2 && $break2->break_end ? \Carbon\Carbon::parse($break2->break_end)->format('H:i') : '') }}"
                                class="admin-attendance-detail__input admin-attendance-detail__input--time"
                            >
                        </div>

                        @error('breaks.1.break_start')
                            <p class="admin-attendance-detail__error">{{ $message }}</p>
                        @enderror

                        @error('breaks.1.break_end')
                            <p class="admin-attendance-detail__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="admin-attendance-detail__row">
                    <div class="admin-attendance-detail__label">備考</div>
                    <div class="admin-attendance-detail__value">
                        <textarea
                            name="note"
                            class="admin-attendance-detail__textarea"
                        >{{ old('note', $attendance->note ?? '') }}</textarea>

                        @error('note')
                            <p class="admin-attendance-detail__error">{{ $message }}</p>
                        @enderror
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