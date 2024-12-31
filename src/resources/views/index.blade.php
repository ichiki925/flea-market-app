<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
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
                <a href="/login">ログイン</a>
                <a href="/mypage">マイページ</a>
                <a href="/sell" class="sell-btn">出品</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <!-- タブ -->
        <div class="tabs-container">
            <div class="tabs">
                <a href="/" class="tab-link active">おすすめ</a>
                <a href="/?tab=mylist" class="tab-link">マイリスト</a>
            </div>
        </div>

        <!-- 商品一覧 -->
        <div class="item-list grid-container">
            @foreach ($items as $item)
                <div class="item">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . rawurlencode($item->item_image)) }}" alt="{{ $item->name }}">
                    </div>
                    <div class="item-name">{{ $item->name }}</div>
                    @if($item->status === 'sold')
                        <div class="item-status">Sold</div>
                    @endif
                </div>
            @endforeach

        </div>
    </main>
</body>

</html>