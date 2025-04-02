@extends('layouts.minimal')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')

    <main class="main">
        <div class="form-container">
            <h1 class="form-title">ログイン</h1>


            <form action="{{ route('login') }}" method="POST" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" required>
                    @if ($errors->has('password'))
                        <div class="error">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <button type="submit" class="btn-submit">ログインする</button>
            </form>
            <p class="link-to-register"><a href="{{ route('register') }}">会員登録はこちら</a></p>
        </div>
    </main>
@endsection
