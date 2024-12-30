@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="{{ asset('css/item_detail.css') }}" />
@endsection

@section('content')
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
@endsection
