<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'COACHTECH')</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__logo">
            <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH">
        </div>

        <nav class="header-nav">
            <a href="{{ route('attendance.index') }}">勤怠</a>
            <a href="{{ route('attendance.list') }}">勤怠一覧</a>
            <a href="{{ route('attendance.corrections.index') }}">申請</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </nav>
    </header>

    <main class="main">
        @yield('content')
    </main>
</body>
</html>