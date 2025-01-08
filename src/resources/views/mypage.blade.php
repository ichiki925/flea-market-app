@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
@endsection

@section('content')
<div class="profile-container">

    <div class="profile-header">
        <div class="image-preview">
            <img src="{{ asset('storage/' . (auth()->user()->profile_image ?? 'user.png')) }}" alt="{{ auth()->user()->name }}" class="profile-preview">
        </div>
        <h2 class="user-name">{{ auth()->user()->name }}</h2>
        <label for="profile_image" class="file-label">
            <a href="{{ route('mypage.profile') }}" class="btn">プロフィールを編集</a>
            <input type="file" name="profile_image" id="profile_image" accept=".jpeg,.png">
        </label>
    </div>

    <div class="tabs-container">
        <div class="tabs">
            <a href="{{ route('mypage', ['tab' => 'sell']) }}" class="tab-link {{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('mypage', ['tab' => 'purchase']) }}" class="tab-link {{ $tab === 'purchase' ? 'active' : '' }}">購入した商品</a>
        </div>
    </div>


        <div class="item-list">
            <div class="grid-container">
                @foreach ($items as $item)
                <div class="item">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}">
                    </div>
                    <div class="item-name">{{ $item->name }}</div>
                </div>
                @endforeach
            </div>
        </div>

</div>
@endsection