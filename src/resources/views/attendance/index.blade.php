@extends('layouts.app')

@section('title', '勤怠打刻画面')

@section('content')
<div class="attendance-page">
    <div class="attendance-content">
        @if (session('message'))
            <p class="attendance-message">{{ session('message') }}</p>
        @endif

        <div class="attendance-status-badge">
            <span>{{ $status }}</span>
        </div>

        <p class="attendance-date">{{ $date }}</p>

        <p class="attendance-time">{{ $time }}</p>

        <div class="attendance-actions">
            @if ($status === '勤務外')
                <form method="POST" action="{{ route('attendance.clock_in') }}">
                    @csrf
                    <button type="submit">出勤</button>
                </form>
            @endif

            @if ($status === '出勤中')
                <div class="attendance-action-row">
                    <form method="POST" action="{{ route('attendance.break_start') }}">
                        @csrf
                        <button type="submit">休憩入</button>
                    </form>

                    <form method="POST" action="{{ route('attendance.clock_out') }}">
                        @csrf
                        <button type="submit">退勤</button>
                    </form>
                </div>
            @endif

            @if ($status === '休憩中')
                <form method="POST" action="{{ route('attendance.break_end') }}">
                    @csrf
                    <button type="submit">休憩戻</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection