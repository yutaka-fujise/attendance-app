@extends('layouts.admin')

@section('title', '申請一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
<link rel="stylesheet" href="{{ asset('css/admin-corrections.css') }}">
@endsection

@section('content')
@php
    $currentStatus = request('status', 'pending');
    $corrections = $currentStatus === 'approved' ? $approvedCorrections : $pendingCorrections;
@endphp

<div class="admin-corrections-page">
    <div class="admin-corrections-container">
        <h1 class="admin-corrections-title">申請一覧</h1>

        <div class="admin-corrections-tabs">
            <a
                href="{{ route('admin.corrections.index', ['status' => 'pending']) }}"
                class="admin-corrections-tab {{ $currentStatus === 'pending' ? 'is-active' : '' }}"
            >
                承認待ち
            </a>
            <a
                href="{{ route('admin.corrections.index', ['status' => 'approved']) }}"
                class="admin-corrections-tab {{ $currentStatus === 'approved' ? 'is-active' : '' }}"
            >
                承認済み
            </a>
        </div>

        <div class="admin-corrections-table-wrap">
            <table class="admin-corrections-table">
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
                    @foreach ($corrections as $correction)
                        <tr>
                            <td>
                                {{ $correction->status === 'pending' ? '承認待ち' : '承認済み' }}
                            </td>
                            <td>{{ $correction->attendance->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($correction->attendance->date)->format('Y/m/d') }}</td>
                            <td>{{ $correction->note }}</td>
                            <td>{{ $correction->created_at->format('Y/m/d') }}</td>
                            <td>
                                <a
                                    href="{{ route('admin.corrections.show', $correction->id) }}"
                                    class="admin-corrections-detail-link"
                                >
                                    詳細
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection