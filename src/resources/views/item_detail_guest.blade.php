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
                    <div class="item-detail__image">
                        <div class="image-placeholder">商品画像</div>
                    </div>
                </div>
                <div class="item-detail__right">
                    <div class="item-detail__info">
                        <h1 class="item-title">商品名がここに入る</h1>
                        <p class="brand-name">ブランド名</p>
                        <p class="price">¥47,000 <span class="tax">(税込)</span></p>
                        <div class="actions">
                            <span class="material-symbols-outlined">
                            star
                            </span>
                            <span class="likes_count"> 3</span>
                            <span class="material-symbols-outlined">
                            chat_bubble
                            </span>
                            <span class="comments_count"> 1</span>
                        </div>
                        <button class="buy-button">購入手続きへ</button>
                    </div>
                    <div class="item-description">
                        <h2>商品説明</h2>
                        <p>カラー：グレー</p>
                        <p>新品<br>商品の状態は良好です。傷もありません。</p>
                        <p>購入後、即発送いたします。</p>
                    </div>
                    <div class="item-info">
                        <h2>商品の情報</h2>
                        <div class="info-row">
                            <span class="info-label">カテゴリー</span>
                            <span class="category-value">洋服</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">商品の状態</span>
                            <span class="status-value">良好</span>
                        </div>
                    </div>
                    <div class="item-comments">
                        <h2>コメント(1)</h2>
                        <div class="comment">
                            <div class="comment-header">
                                <img src="{{ asset('images/user.png') }}" alt="User" class="comment-avatar">
                                <span class="comment-author">admin</span>
                            </div>
                            <p class="comment-text">こちらにコメントが入ります。</p>
                        </div>
                        <label for="comment-input" class="comment-label">商品へのコメント</label>
                        <textarea id="comment-input" class="comment-input"></textarea>
                        <button class="comment-submit">コメントを送信する</button>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>

