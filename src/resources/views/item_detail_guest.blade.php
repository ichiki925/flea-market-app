<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>フリマアプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="{{ asset('css/item_detail_guest.css') }}" />
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
            <div class="item-detail">
                <div class="item-detail__left">
                    <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}" class="item-detail__image">
                </div>
                <div class="item-detail__right">
                    <div class="item-detail__info">
                        <h1 class="item-title">{{ $item->name }}</h1>
                        <p class="brand-name">{{ $item->brand ?? '' }}</p>
                        <p class="price">¥{{ number_format($item->price) }} <span class="tax">(税込)</span></p>
                        <div class="actions">
                            <span class="material-symbols-outlined" style="color: black; cursor: default;">
                                star
                            </span>
                            <span class="likes_count">{{ $item->likes->count() }}</span>
                            <span class="material-symbols-outlined">
                            chat_bubble
                            </span>
                            <span class="comments_count">{{ $item->comments->count() }}</span>
                        </div>
                        <button class="buy-button">購入手続きへ</button>
                    </div>
                    <div class="item-description">
                        <h2>商品説明</h2>
                        <p>{{ $item->description }}</p>
                    </div>
                    <div class="item-info">
                        <h2>商品の情報</h2>
                        <div class="info-row">
                            <span class="info-label">カテゴリー</span>
                            <span class="category-value">{{ $item->categories->pluck('name')->join(', ') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">商品の状態</span>
                            <span class="status-value">{{ $item->condition->name ?? '未設定' }}</span>
                        </div>
                    </div>
                    <div class="item-comments">
                        <h2>コメント({{ $item->comments->count() }})</h2>
                        @foreach($item->comments as $comment)
                            <div class="comment">
                                <div class="comment-header">
                                    <img src="{{ asset('images/user.png') }}" alt="User" class="comment-avatar">
                                    <span class="comment-author">{{ $comment->user->name }}</span>
                                </div>
                                <p class="comment-text">{{ $comment->content }}</p>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </main>
    </body>
</html>

