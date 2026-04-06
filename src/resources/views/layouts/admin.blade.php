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
            <a href="{{ route('admin.attendances.index') }}">勤怠一覧</a>
            <a href="#">スタッフ一覧</a>
            <a href="{{ route('admin.corrections.index') }}">申請一覧</a>

            <form method="POST" action="{{ route('admin.logout') }}">
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