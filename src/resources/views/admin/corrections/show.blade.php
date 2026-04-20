@extends('layouts.admin')

@section('title', '勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
<link rel="stylesheet" href="{{ asset('css/admin-correction-show.css') }}">
@endsection

@section('content')
<div class="admin-correction-show">
    <div class="admin-correction-show__inner">
        <h1 class="admin-correction-show__title">勤怠詳細</h1>

        <div class="admin-correction-show__table">
            <div class="admin-correction-show__row">
                <div class="admin-correction-show__label">名前</div>
                <div class="admin-correction-show__value admin-correction-show__value--name">
                    {{ $correction->user->name }}
                </div>
            </div>

            <div class="admin-correction-show__row">
                <div class="admin-correction-show__label">日付</div>
                <div class="admin-correction-show__value admin-correction-show__value--date">
                    <span>{{ \Carbon\Carbon::parse($correction->attendance->date)->format('Y年') }}</span>
                    <span>{{ \Carbon\Carbon::parse($correction->attendance->date)->format('n月j日') }}</span>
                </div>
            </div>

            <div class="admin-correction-show__row">
                <div class="admin-correction-show__label">出勤・退勤</div>
                <div class="admin-correction-show__value admin-correction-show__value--time-range">
                    <span>{{ \Carbon\Carbon::parse($correction->clock_in)->format('H:i') }}</span>
                    <span class="admin-correction-show__separator">〜</span>
                    <span>{{ \Carbon\Carbon::parse($correction->clock_out)->format('H:i') }}</span>
                </div>
            </div>

            @foreach ($correction->correctionBreaks as $index => $break)
                <div class="admin-correction-show__row">
                    <div class="admin-correction-show__label">
                        {{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}
                    </div>
                    <div class="admin-correction-show__value admin-correction-show__value--time-range">
                        <span>{{ \Carbon\Carbon::parse($break->break_start)->format('H:i') }}</span>
                        <span class="admin-correction-show__separator">〜</span>
                        <span>{{ \Carbon\Carbon::parse($break->break_end)->format('H:i') }}</span>
                    </div>
                </div>
            @endforeach

            <div class="admin-correction-show__row">
                <div class="admin-correction-show__label">備考</div>
                <div class="admin-correction-show__value admin-correction-show__value--note">
                    {{ $correction->note }}
                </div>
            </div>
        </div>

        <div class="admin-correction-show__button-wrap">
            @if ($correction->status === 'pending')
                <form method="POST" action="{{ route('admin.corrections.approve', $correction->id) }}">
                    @csrf
                    <button type="submit" class="admin-correction-show__button">
                        承認
                    </button>
                </form>
            @else
                <button type="button" class="admin-correction-show__button admin-correction-show__button--approved" disabled>
                    承認済み
                </button>
            @endif
        </div>
    </div>
</div>
@endsection