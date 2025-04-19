@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
@endsection

@section('content')
<div class="profile-container">

    <div class="profile-header">
        <div class="image-preview">
            @if(optional(auth()->user()->profile)->img_url)
                <img src="{{ asset('storage/' . auth()->user()->profile->img_url) }}" alt="プロフィール画像" class="profile-preview">
            @else
                <img src="{{ asset('images/default-placeholder.png') }}" alt="デフォルト画像" class="profile-preview">
            @endif
        </div>

        <div class="user-info">
            <h2 class="user-name">{{ auth()->user()->name }}</h2>

            <div class="star-rating">
                @php
                    $rating = round(auth()->user()->average_rating ?? 0);
                @endphp
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $rating)
                        <span class="star filled">★</span>
                    @else
                        <span class="star">★</span>
                    @endif
                @endfor
            </div>
        </div>

        <label for="profile_image" class="file-label">
            <a href="{{ route('mypage.profile') }}" class="btn">プロフィールを編集</a>
            <input type="file" name="profile_image" id="profile_image" accept=".jpeg,.png">
        </label>
    </div>

    <div class="tabs-container">
        <div class="tabs">
            <a href="{{ route('mypage', ['tab' => 'sell']) }}" class="tab-link {{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('mypage.purchases') }}" class="tab-link {{ $tab === 'purchase' ? 'active' : '' }}">購入した商品</a>
            <a href="{{ route('mypage.trading') }}" class="tab-link {{ $tab === 'trading' ? 'active' : '' }}">
                取引中の商品
                @if (!empty($unreadCounts) && array_sum($unreadCounts) > 0)
                    <span class="badge">{{ array_sum($unreadCounts) }}</span>
                @endif
            </a>

        </div>
    </div>


        <div class="item-list">
            <div class="grid-container">
                @foreach ($items as $item)
                    <div class="item">
                        <a href="{{ route('chat.show', $item->id) }}">
                            <div class="item-image">
                                <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" loading="lazy">

                                {{-- 未読件数があれば表示 --}}
                                @if (!empty($unreadCounts[$item->id]) && $unreadCounts[$item->id] > 0)
                                    <span class="unread-badge">{{ $unreadCounts[$item->id] }}</span>
                                @endif

                            </div>
                            <div class="item-name">{{ $item->name }}</div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

</div>
@endsection