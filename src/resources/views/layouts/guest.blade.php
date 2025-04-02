<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="{{ route('index') }}">
                    <img src="{{ asset('img/logo.svg') }}" alt="Logo">
                </a>
            </div>
            <form class="search-form" action="{{ route('index') }}" method="GET">
                <input type="text" name="search" placeholder="なにをお探しですか？" value="{{ request('search') }}">
            </form>
            <nav class="nav">
                <a href="{{ route('login') }}">ログイン</a>
                <a href="{{ route('mypage') }}">マイページ</a>
                <a href="/sell" class="sell-btn">出品</a>
            </nav>
        </div>
    </header>

    @yield('content')
</body>

</html>
