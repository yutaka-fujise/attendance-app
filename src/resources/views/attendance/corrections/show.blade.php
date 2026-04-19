@extends('layouts.app')

@section('title', '申請詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/attendance-show.css') }}">
@endsection

@section('content')
<div class="attendance-detail-page">
    <div class="attendance-detail-container">
        <h1 class="attendance-detail-title">申請詳細</h1>

        <div class="detail-table">
            <div class="detail-row">
                <div class="detail-label">名前</div>
                <div class="detail-value">
                    {{ $correction->attendance->user->name }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">日付</div>
                <div class="detail-value detail-date-group">
                    <span>{{ \Carbon\Carbon::parse($correction->attendance->date)->format('Y年') }}</span>
                    <span>{{ \Carbon\Carbon::parse($correction->attendance->date)->format('n月j日') }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">出勤・退勤</div>
                <div class="detail-value detail-time-group">
                    <span>{{ \Carbon\Carbon::parse($correction->clock_in)->format('H:i') }}</span>
                    <span class="time-separator">〜</span>
                    <span>{{ \Carbon\Carbon::parse($correction->clock_out)->format('H:i') }}</span>
                </div>
            </div>

            @foreach ($correction->correctionBreaks as $index => $break)
                <div class="detail-row">
                    <div class="detail-label">
                        {{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}
                    </div>
                    <div class="detail-value detail-time-group">
                        <span>{{ $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}</span>
                        <span class="time-separator">〜</span>
                        <span>{{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}</span>
                    </div>
                </div>
            @endforeach

            <div class="detail-row">
                <div class="detail-label">備考</div>
                <div class="detail-value">
                    {{ $correction->note }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">状態</div>
                <div class="detail-value">
                    {{ $correction->status === 'pending' ? '承認待ち' : '承認済み' }}
                </div>
            </div>
        </div>

        {{-- 戻るボタン --}}
        <div class="detail-button-area">
            <a href="{{ route('attendance.show', $correction->attendance_id) }}"
               class="detail-submit-button">
                ←戻る
            </a>
        </div>

    </div>
</div>
@endsection