@extends('layouts.app')

@section('title', '勤怠詳細')

@section('content')
<div class="attendance-detail-page">
    <div class="attendance-detail-container">
        <h1 class="attendance-detail-title">勤怠詳細</h1>

        @if (session('message'))
            <div class="attendance-message">
                {{ session('message') }}
            </div>
        @endif

        @php
            $isLocked = $latestCorrection !== null;
        @endphp

        <form method="POST" action="{{ route('attendance.update', $attendance->id) }}" class="attendance-detail-form">
            @csrf

            <div class="detail-table">
                <div class="detail-row">
                    <div class="detail-label">名前</div>
                    <div class="detail-value">
                        {{ $attendance->user->name }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">日付</div>
                    <div class="detail-value detail-date-group">
                        <span>{{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}</span>
                        <span>{{ \Carbon\Carbon::parse($attendance->date)->format('n月j日') }}</span>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">出勤・退勤</div>
                    <div class="detail-value detail-time-group">
                        <input
                            type="time"
                            name="clock_in"
                            value="{{ old('clock_in', $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '') }}"
                            class="time-input"
                            {{ $isLocked ? 'disabled' : '' }}
                        >
                        <span class="time-separator">〜</span>
                        <input
                            type="time"
                            name="clock_out"
                            value="{{ old('clock_out', $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '') }}"
                            class="time-input"
                            {{ $isLocked ? 'disabled' : '' }}
                        >
                    </div>

                    @error('clock_in')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                    @error('clock_out')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                @for ($i = 0; $i < 2; $i++)
                    @php
                        $break = $attendance->breaks[$i] ?? null;
                    @endphp

                    <div class="detail-row">
                        <div class="detail-label">
                            {{ $i === 0 ? '休憩' : '休憩' . ($i + 1) }}
                        </div>

                        <div class="detail-value detail-time-group">
                            <input
                                type="time"
                                name="breaks[{{ $i }}][break_start]"
                                value="{{ old("breaks.$i.break_start", $break && $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '') }}"
                                class="time-input"
                                {{ $isLocked ? 'disabled' : '' }}
                            >
                            <span class="time-separator">〜</span>
                            <input
                                type="time"
                                name="breaks[{{ $i }}][break_end]"
                                value="{{ old("breaks.$i.break_end", $break && $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '') }}"
                                class="time-input"
                                {{ $isLocked ? 'disabled' : '' }}
                            >
                        </div>

                        @error("breaks.$i.break_start")
                            <p class="error-message">{{ $message }}</p>
                        @enderror

                        @error("breaks.$i.break_end")
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                @endfor

                <div class="detail-row">
                    <div class="detail-label">備考</div>
                    <div class="detail-value">
                        <textarea
                            name="note"
                            class="note-textarea"
                            {{ $isLocked ? 'disabled' : '' }}
                        >{{ old('note') }}</textarea>
                    </div>

                    @error('note')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if ($latestCorrection)
                <div class="detail-message-area">
                    @if ($latestCorrection->status === 'pending')
                        <p class="pending-message">※申請済みのため修正できません。</p>
                    @elseif ($latestCorrection->status === 'approved')
                        <p class="pending-message">※承認済みのため修正できません。</p>
                    @endif
                </div>
            @else
                <div class="detail-button-area">
                    <button type="submit" class="detail-submit-button">修正</button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection