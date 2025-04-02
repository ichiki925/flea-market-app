<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/minimal.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header minimal-header">
        <div class="container">
            <div class="logo">
                <a href="{{ route('index') }}">
                    <img src="{{ asset('img/logo.svg') }}" alt="Logo">
                </a>
            </div>
        </div>
    </header>

    @yield('content')
</body>

</html>
