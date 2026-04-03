@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login-page">
    <div class="login-container">
        <h1 class="login-title">ログイン</h1>

        <form method="POST" action="{{ route('login') }}" class="login-form">
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
            <a href="{{ route('register') }}" class="register-link">会員登録はこちら</a>
        </div>
    </div>
</div>
@endsection