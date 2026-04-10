@extends('layouts.app')

@section('title', '申請一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
<link rel="stylesheet" href="{{ asset('css/corrections.css') }}">
@endsection

@section('content')
    @php
        $currentStatus = request('status', 'pending');
        $corrections = $currentStatus === 'approved' ? $approvedCorrections : $pendingCorrections;
    @endphp

    <div class="correction-index-page">
        <div class="correction-index-container">
            <h1 class="correction-index-title">申請一覧</h1>

            <div class="correction-index-tabs">
                <a
                    href="{{ route('attendance.corrections.index', ['status' => 'pending']) }}"
                    class="correction-index-tab {{ $currentStatus === 'pending' ? 'is-active' : '' }}"
                >
                    承認待ち
                </a>
                <a
                    href="{{ route('attendance.corrections.index', ['status' => 'approved']) }}"
                    class="correction-index-tab {{ $currentStatus === 'approved' ? 'is-active' : '' }}"
                >
                    承認済み
                </a>
            </div>

            <div class="correction-index-table-wrap">
                <table class="correction-index-table">
                    <thead>
                        <tr>
                            <th>状態</th>
                            <th>名前</th>
                            <th>対象日時</th>
                            <th>申請理由</th>
                            <th>申請日時</th>
                            <th>詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($corrections as $correction)
                            <tr>
                                <td>{{ $currentStatus === 'pending' ? '承認待ち' : '承認済み' }}</td>
                                <td>{{ $correction->attendance->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($correction->attendance->date)->format('Y/m/d') }}</td>
                                <td>{{ $correction->note }}</td>
                                <td>{{ \Carbon\Carbon::parse($correction->created_at)->format('Y/m/d') }}</td>
                                <td>
                                    <a href="{{ route('attendance.show', $correction->attendance->id) }}">
                                        詳細
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="correction-index-empty">
                                    申請はありません
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection