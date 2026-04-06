@extends('layouts.admin')

@section('title', '申請一覧（管理者）')

@section('content')
<h1>申請一覧（管理者）</h1>

<h2>承認待ち（{{ $pendingCorrections->count() }}件）</h2>

<table>
    <thead>
        <tr>
            <th>名前</th>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>ステータス</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pendingCorrections as $correction)
            <tr onclick="location.href='{{ route('admin.corrections.show', $correction->id) }}'" style="cursor: pointer;">
                <td>{{ $correction->user->name }}</td>
                <td>{{ $correction->attendance->date }}</td>
                <td>{{ $correction->clock_in }}</td>
                <td>{{ $correction->clock_out }}</td>
                <td>{{ $correction->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h2>承認済み（{{ $approvedCorrections->count() }}件）</h2>

<table>
    <thead>
        <tr>
            <th>名前</th>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>ステータス</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($approvedCorrections as $correction)
            <tr onclick="location.href='{{ route('admin.corrections.show', $correction->id) }}'" style="cursor: pointer;">
                <td>{{ $correction->user->name }}</td>
                <td>{{ $correction->attendance->date }}</td>
                <td>{{ $correction->clock_in }}</td>
                <td>{{ $correction->clock_out }}</td>
                <td>{{ $correction->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection