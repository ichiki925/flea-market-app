<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>


<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo">
            </div>
            <form class="search-form">
                <input type="text" placeholder="なにをお探しですか？">
            </form>
            <nav class="nav">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">ログアウト</button>
                </form>
                <a href="/mypage">マイページ</a>
                <a href="/sell" class="sell-btn">出品</a>
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>