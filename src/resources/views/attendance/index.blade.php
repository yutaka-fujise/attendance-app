<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>勤怠打刻画面</title>
</head>
<body>
<h1>勤怠打刻画面</h1>
@if (session('message'))
    <p>{{ session('message') }}</p>
@endif

<p>日付：{{ $date }}</p>
<p>時刻：{{ $time }}</p>
<p>ステータス：{{ $status }}</p>

@if ($status === '勤務外')
    <form method="POST" action="{{ route('attendance.clock_in') }}">
        @csrf
        <button type="submit">出勤</button>
    </form>
@endif

@if ($status === '出勤中')
    <form method="POST" action="{{ route('attendance.break_start') }}">
        @csrf
        <button type="submit">休憩入</button>
    </form>
@endif

@if ($status === '休憩中')
    <form method="POST" action="{{ route('attendance.break_end') }}">
        @csrf
        <button type="submit">休憩戻</button>
    </form>
@endif

@if ($status === '出勤中')
    <form method="POST" action="{{ route('attendance.clock_out') }}">
        @csrf
        <button type="submit">退勤</button>
    </form>
@endif
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">ログアウト</button>
</form>
</body>
</html>