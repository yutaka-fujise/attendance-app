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

        <form method="POST" action="{{ route('admin.login.store') }}" class="login-form">
            @csrf

            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input type="email" name="email" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">パスワード</label>
                <input type="password" name="password" class="form-input">
            </div>

            <button type="submit" class="login-button">ログインする</button>
        </form>

        <div class="register-link-wrap">
            <a href="/login" class="register-link">一般ユーザーの方はこちら</a>
        </div>
    </div>
</div>
@endsection