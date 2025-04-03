@extends('layouts.guest')

@section('css')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="{{ asset('css/item_detail_guest.css') }}">
@endsection

@section('content')

    <main class="main">
        <div class="item-detail">
            <div class="item-detail__left">
                <img src="{{ asset('storage/' . $item->img_url) }}" alt="商品画像：{{ $item->name }}" class="item-detail__image" loading="lazy">
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
                        <div class="category-values">
                            @foreach($item->categories as $category)
                                <span class="category-value">{{ $category->category }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="info-row">
                        <span class="info-label">商品の状態</span>
                        <span class="status-value">{{ $item->condition->condition ?? '未設定' }}</span>
                    </div>
                </div>
                <div class="item-comments">
                    <h2>コメント({{ $item->comments->count() }})</h2>
                    @foreach($item->comments as $comment)
                        <div class="comment">
                            <div class="comment-header">
                                <img src="{{ asset('img/user.png') }}" alt="User" class="comment-avatar">
                                <span class="comment-author">{{ $comment->user->name }}</span>
                            </div>
                            <p class="comment-text">{{ $comment->comment }}</p>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </main>
@endsection

