@extends('layouts.guest')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
<div class="verify page">

  <div class="verify-box">

    <p class="verify-message">
      登録していただいたメールアドレスに認証メールを送付しました。<br>
      メール認証を完了してください。
    </p>

    <div class="verify-button-area">
  @if(app()->environment('local'))
    <a
      href="http://localhost:8025"
      target="_blank"
      rel="noopener"
      class="verify-button"
    >
      認証はこちらから
    </a>
  @endif
</div>

    <form method="POST" action="/email/verification-notification">
      @csrf
      <button type="submit" class="verify-resend">
        認証メールを再送する
      </button>
    </form>

  </div>

</div>
@endsection