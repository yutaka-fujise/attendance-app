@extends('layouts.app')

@section('title', '会員登録')

@section('content')
<div class="register-page">
    <div class="register-container">
        <h1 class="register-title">会員登録</h1>

        <form method="POST" action="{{ route('register') }}" class="register-form">
            @csrf

            <div class="form-group">
                <label class="form-label">名前</label>
                <input type="text" name="name" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input type="email" name="email" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">パスワード</label>
                <input type="password" name="password" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">パスワード確認</label>
                <input type="password" name="password_confirmation" class="form-input">
            </div>

            <button type="submit" class="register-button">登録する</button>
        </form>

        <div class="login-link-wrap">
            <a href="{{ route('login') }}" class="login-link">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection