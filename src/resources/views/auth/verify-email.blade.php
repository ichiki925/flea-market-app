@extends('layouts.minimal')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')

    <main class="main-content">

        <p>登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。</p>

        <a href="http://localhost:8025" class="verify-button">認証はこちらから</a>


        <form method="POST" action="{{ route('verification.send') }}" class="resend-form">
            @csrf
            <button type="submit" class="resend-button">認証メールを再送する</button>
        </form>


        @if (auth()->user() && auth()->user()->hasVerifiedEmail())
            <script>
                window.location.href = "{{ route('attendance.register') }}";
            </script>
        @endif
    </main>

@endsection
