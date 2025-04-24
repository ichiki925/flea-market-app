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
                    <a href="{{ route('chat.show', $myItem->id) }}">{{ $myItem->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>

    <main class="chat-main">
        <div class="chat-header">
            <div class="chat-header-top">
                <div class="user-info">
                    <div class="user-icon">
                        @if ($partner && $partner->profile && $partner->profile->img_url)
                            <img src="{{ asset('storage/' . $partner->profile->img_url) }}" alt="">
                        @else
                            <img src="{{ asset('img/default.png') }}" alt="">
                        @endif
                    </div>
                    <h3>「{{ $partner ? $partner->name : '取引相手なし' }}」さんとの取引画面</h3>
                </div>
                @if ($isBuyer && !$buyerReviewDone)
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

        <div class="chat-messages">
            @foreach ($messages as $msg)
                @php
                    $isEditMode = isset($message) && request()->routeIs('chat.edit') && $message->id === $msg->id;
                @endphp

                <div class="chat-message {{ $msg->user_id === auth()->id() ? 'mine' : 'other' }}">

                    <div class="message-header">
                        @if ($msg->user && $msg->user->profile && $msg->user->profile->img_url)
                            <img src="{{ asset('storage/' . $msg->user->profile->img_url) }}" class="message-icon">
                        @else
                            <div class="message-icon"></div>
                        @endif
                        <span class="message-user">{{ $msg->user->id === auth()->id() ? auth()->user()->name : ($msg->user->name ?? '不明なユーザー') }}</span>
                    </div>


                    @if ($isEditMode)
                        <form method="POST" action="{{ route('chat.update', $msg->id) }}" enctype="multipart/form-data" class="chat-form is-edit">
                            @csrf
                            @method('PUT')


                            <input type="text" name="message" value="{{ old('message', $msg->message) }}">


                            @if ($msg->image_path)
                                <div class="message-image">
                                    <img src="{{ asset('storage/' . $msg->image_path) }}" alt="画像" style="max-width: 200px;">
                                </div>
                            @endif


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
                    @endif
                </div>
            @endforeach
            <div id="preview-container"></div>
        </div>

        @if ($errors->any())
            <div class="error-messages">
                @foreach ($errors->all() as $error)
                    <div class="error-message">{{ $error }}</div>
                @endforeach
            </div>
        @endif


        @if (!isset($message))
        <form class="chat-form" method="POST" action="{{ route('chat.send', $item->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="text" name="message" placeholder="取引メッセージを記入してください" value="{{ session('message_input', old('message')) }}">
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
    const username = window.username ?? '自分';
    const defaultIcon = window.defaultIcon ?? 'img/default.png';
    const previewContainer = document.getElementById('preview-container');
    const fileInput = document.querySelector('input[name="image"]');
    const textInput = document.querySelector('input[name="message"]');
    const form = document.querySelector('form.chat-form');

        if (localStorage.getItem('messageInput')) {
            textInput.value = localStorage.getItem('messageInput');
        }

        textInput.addEventListener('input', function () {
            localStorage.setItem('messageInput', textInput.value);
        });

        form.addEventListener('submit', function () {
            localStorage.removeItem('messageInput');
        });

        textInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const value = textInput.value.trim();
                if (!value) return;

                const div = document.createElement('div');
                div.className = 'chat-message mine';
                div.innerHTML = `
                    <div class="message-header">
                        <img src="${defaultIcon}" class="message-icon">
                        <span class="message-user">${username}</span>
                    </div>
                    <div class="message-text">${value}</div>
                `;
                previewContainer.appendChild(div);
                textInput.value = '';
            }
        });

        fileInput?.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const div = document.createElement('div');
                div.className = 'chat-message mine';
                div.innerHTML = `
                    <div class="message-header">
                        <img src="${defaultIcon}" class="message-icon">
                        <span class="message-user">${username}</span>
                    </div>
                    <div class="message-image">
                        <img src="${e.target.result}" alt="プレビュー画像" style="max-width: 200px; border-radius: 6px;">
                    </div>
                `;
                previewContainer.appendChild(div);

                setTimeout(() => {
                    document.querySelector('form.chat-form')?.submit();
                }, 500);
            };
            reader.readAsDataURL(file);
        });
    });
</script>

@if ($isSeller && $buyerReviewDone && !$sellerReviewDone)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.location.hash = 'rating-modal';
        });
    </script>
@endif

@endsection
