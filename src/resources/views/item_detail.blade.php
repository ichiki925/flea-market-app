@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="{{ asset('css/item_detail.css') }}" />
@endsection

@section('content')
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
                <form action="{{ route('likes.toggle', ['itemId' => $item->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                        <span class="material-symbols-outlined" style="color: {{ $item->likes->contains('user_id', auth()->id()) ? 'red' : 'black' }};">
                            star
                        </span>
                    </button>
                </form>
                <span class="likes_count">{{ $item->likes->count() }}</span>

                <span class="material-symbols-outlined">
                chat_bubble
                </span>
                <span class="comments_count">{{ $item->comments->count() }}</span>
            </div>

        <form action="{{ route('purchase.show', ['item_id' => $item->id]) }}" method="GET" style="display: inline;">
            <button type="submit" class="buy-button">購入手続きへ</button>
        </form>

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

            @auth
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <label for="comment-input" class="comment-label">商品へのコメント</label>
                <textarea id="comment-input" name="comment" class="comment-input">{{ old('comment') }}</textarea>
                @if ($errors->has('comment'))
                    <p class="error-message">{{ $errors->first('comment') }}</p>
                @endif
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <button type="submit" class="comment-submit">コメントを送信する</button>
            </form>
            @endauth
        </div>
    </div>
</div>
@endsection
