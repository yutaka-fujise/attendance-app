@extends('layouts.guest')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login-page">
    <div class="login-container">

        <h1 class="login-title">管理者ログイン</h1>

        @if ($errors->has('email') && str_contains($errors->first('email'), '登録されていません'))
            <div class="form-error-top">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}" class="login-form" novalidate>
            @csrf

            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-input"
                >
                @error('email')
                    @if (!str_contains($message, '登録されていません'))
                        <p class="form-error">{{ $message }}</p>
                    @endif
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">パスワード</label>
                <input
                    type="password"
                    name="password"
                    class="form-input"
                >
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="login-button">ログインする</button>
        </form>

        <div class="register-link-wrap">
            <a href="{{ route('login') }}" class="register-link">一般ユーザーの方はこちら</a>
        </div>

    </div>
</div>
@endsection