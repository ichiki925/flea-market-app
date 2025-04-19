@extends('layouts.minimal')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
<div class="chat-container">


<div class="sidebar">
    <h2 class="sidebar-title">その他の取引</h2>
    <ul class="sidebar-items">
        @foreach ($myItems as $myItem)
            <li class="sidebar-item">
                <a href="{{ route('chat.show', $myItem->id) }}">
                    {{ $myItem->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<main class="chat-main">

    {{-- 商品情報 --}}
    <div class="chat-header">
        <div class="chat-header-top">
            <div class="user-info">
                <div class="user-icon">
                    <img src="{{ optional($partner->profile)->img_url ? asset('storage/' . $partner->profile->img_url) : asset('images/default-placeholder.png') }}" alt="">
                </div>
                <h3>「{{ $partner->name }}」さんとの取引画面</h3>
            </div>
            @if ($isBuyer)
                <a href="#rating-modal" class="complete-button">取引を完了する</a>
            @endif
        </div>

        <div class="item-info">
            <img src="{{ asset('storage/' . $item->img_url) }}" alt="商品画像" class="item-image">
            <div class="item-text">
                <h2>{{ $item->name }}</h2>
                <p>¥{{ number_format($item->price) }}</p>
            </div>
        </div>
    </div>

    {{-- メッセージ表示 --}}
    <div class="chat-messages">
            @foreach ($messages as $msg)
                @php
                    $isEditMode = isset($message) && request()->routeIs('chat.edit') && $message->id === $msg->id;
                @endphp


                <div class="chat-message {{ $msg->user_id === auth()->id() ? 'mine' : 'other' }}">
                    <div class="message-header">
                        <img
                            src="{{ optional($msg->user->profile)->img_url
                                ? asset('storage/' . $msg->user->profile->img_url)
                                : asset('images/default-placeholder.png') }}"
                            class="message-icon">
                        <span class="message-user">{{ $msg->user->name }}</span>
                    </div>

                    @if ($isEditMode)
                        <form method="POST" action="{{ route('chat.update', $msg->id) }}" enctype="multipart/form-data" class="chat-form is-edit">
                            @csrf
                            @method('PUT')
                            <input type="text" name="message" value="{{ old('message', $msg->message) }}">
                            <label class="image-upload">
                                画像を追加
                                <input type="file" name="image" accept="image/*">
                            </label>
                            <button type="submit" class="send-button">
                                <img src="{{ asset('img/send-icon.png') }}" alt="更新" class="send-icon">
                            </button>
                        </form>
                    @else
                        @if ($msg->message)
                            <div class="message-text">{{ $msg->message }}</div>
                        @endif
                        @if ($msg->image_path)
                            <div class="message-image">
                                <img src="{{ asset('storage/' . $msg->image_path) }}" alt="画像" style="max-width: 200px;">
                            </div>
                        @endif
                    @endif

                    @if ($msg->user_id === auth()->id())
                        <div class="message-actions">
                            <a href="{{ route('chat.edit', $msg->id) }}">編集</a>
                            <form action="{{ route('chat.destroy', $msg->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">削除</button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach

            <div id="preview-container"></div>
        </div>

        {{-- バリデーションエラー表示 --}}
        @if ($errors->any())
            <div class="error-messages">
                @foreach ($errors->all() as $error)
                    <div class="error-message">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- 通常の送信フォーム（編集モードでないときだけ） --}}
        @if (!isset($message))
        <form class="chat-form {{ $isEditMode ? 'is-edit' : '' }}"
        method="POST"
        action="{{ $isEditMode ? route('chat.update', $message->id) : route('chat.send', $item->id) }}"
        enctype="multipart/form-data">
            @csrf
            <input type="text" name="message" placeholder="取引メッセージを記入してください" value="{{ old('message') }}">
            <label class="image-upload">
                画像を追加
                <input type="file" name="image" accept="image/*">
            </label>
            <button type="submit" class="send-button">
                <img src="{{ asset('img/send-icon.png') }}" alt="送信" class="send-icon">
            </button>
        </form>
        @endif

    </main>
</div>

{{-- モーダル（評価用） --}}
<div class="modal" id="rating-modal">
    <a href="#!" class="modal-overlay"></a>
    <div class="modal__inner">
        <h3 class="modal__title">取引が完了しました。</h3>
        <hr class="modal__divider">
        <p class="modal__text">今回の取引相手はどうでしたか？</p>
        <form action="{{ route('rating.submit', $item->id) }}" method="POST">
            @csrf
            <div class="rating-stars">
                @for ($i = 5; $i >= 1; $i--)
                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                    <label for="star{{ $i }}">&#9733;</label>
                @endfor
            </div>
            <hr class="modal__divider">
            <div class="submit-area">
                <button type="submit" class="submit-rating">送信する</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const previewContainer = document.getElementById('preview-container');
    const fileInput = document.querySelector('input[name="image"]');
    const textInput = document.querySelector('input[name="message"]');

    // テキスト入力時にエンターで仮メッセージを出す（送信前プレビュー）
    textInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            const value = textInput.value.trim();
            if (value === '') return;

            const div = document.createElement('div');
            div.className = 'chat-message mine';
            div.innerHTML = `
                <div class="message-header">
                    <img src="/images/default-placeholder.png" class="message-icon">
                    <span class="message-user">自分</span>
                </div>
                <div class="message-text">${value}</div>
            `;
            previewContainer.appendChild(div);
            textInput.value = '';
        }
    });

    // 画像選択時に仮の画像吹き出しを追加
    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const div = document.createElement('div');
            div.className = 'chat-message mine';
            div.innerHTML = `
                <div class="message-header">
                    <img src="/images/default-placeholder.png" class="message-icon">
                    <span class="message-user">自分</span>
                </div>
                <div class="message-image">
                    <img src="${e.target.result}" alt="プレビュー画像" style="max-width: 200px; max-height: 200px; border-radius: 6px;">
                </div>
            `;
            previewContainer.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>




@endsection
