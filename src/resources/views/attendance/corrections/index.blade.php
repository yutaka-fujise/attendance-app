@extends('layouts.app')

@section('title', '申請一覧')

@section('content')
<div class="correction-index-page">
    <div class="correction-index-container">
        <h1 class="correction-index-title">申請一覧</h1>

        <h2 class="correction-section-title">承認待ち（{{ $pendingCorrections->count() }}件）</h2>
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
                    @foreach ($pendingCorrections as $correction)
                        <tr>
                            <td>承認待ち</td>
                            <td>{{ $correction->attendance->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($correction->attendance->date)->format('Y/m/d') }}</td>
                            <td>{{ $correction->note }}</td>
                            <td>{{ \Carbon\Carbon::parse($correction->created_at)->format('Y/m/d') }}</td>
                            <td>
                                <a href="{{ route('attendance.show', $correction->attendance->id) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h2 class="correction-section-title">承認済み（{{ $approvedCorrections->count() }}件）</h2>
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
                    @foreach ($approvedCorrections as $correction)
                        <tr>
                            <td>承認済み</td>
                            <td>{{ $correction->attendance->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($correction->attendance->date)->format('Y/m/d') }}</td>
                            <td>{{ $correction->note }}</td>
                            <td>{{ \Carbon\Carbon::parse($correction->created_at)->format('Y/m/d') }}</td>
                            <td>
                                <a href="{{ route('attendance.show', $correction->attendance->id) }}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection