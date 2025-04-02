@extends('layouts.minimal')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')

    <main class="main">
        <div class="form-container">
            <h1 class="form-title">会員登録</h1>
            <form action="{{ route('register') }}" method="POST" novalidate>
                @csrf
                <div class="form-group">
                    <label for="name">ユーザー名</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" name="password" required>
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">確認用パスワード</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>
                <button type="submit" class="btn-submit">登録する</button>
            </form>
            <p class="link-to-login"><a href="{{ route('login') }}">ログインはこちら</a></p>
        </div>
    </main>
@endsection
