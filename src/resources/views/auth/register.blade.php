@extends('layouts.app')

@section('title', '会員登録')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <div class="register-page">
        <div class="register-container">
            <h1 class="register-title">会員登録</h1>

            <form method="POST" action="{{ route('register') }}" class="register-form">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">名前</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-input"
                    >
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-input"
                    >
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">パスワード</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-input"
                    >
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">パスワード確認</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-input"
                    >
                </div>

                <div class="register-button-area">
                    <button type="submit" class="register-button">登録する</button>
                </div>
            </form>

            <div class="register-login-link-area">
                <a href="{{ route('login') }}" class="register-login-link">ログインはこちら</a>
            </div>
        </div>
    </div>
@endsection