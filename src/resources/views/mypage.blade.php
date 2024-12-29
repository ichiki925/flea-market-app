@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
@endsection

@section('content')
<div class="profile-container">

    <div class="profile-header">
        <div class="image-preview">
            <img src="{{ old('profile_image') ?? asset('storage/user.png') }}" alt="ユーザー名" class="profile-preview">
        </div>
        <h2 class="user-name">ユーザー名</h2>
        <label for="profile_image" class="file-label">
            プロフィールを編集
            <input type="file" name="profile_image" id="profile_image" accept=".jpeg,.png">
        </label>
    </div>

    <div class="tabs-container">
        <div class="tabs">
            <a href="/" class="tab-link active">出品した商品</a>
            <a href="/?tab=mylist" class="tab-link">購入した商品</a>
        </div>
    </div>


        <div class="item-list">
            @php
            // ダミーデータを作成
            $items = [
                (object)[
                    'image_path' => 'default-item.png',
                    'name' => 'サンプル商品1'
                ],
                (object)[
                    'image_path' => 'default-item.png',
                    'name' => 'サンプル商品2'
                ],
                (object)[
                    'image_path' => 'default-item.png',
                    'name' => 'サンプル商品3'
                ],
                (object)[
                    'image_path' => 'default-item.png',
                    'name' => 'サンプル商品4'
                ],
                (object)[
                    'image_path' => 'default-item.png',
                    'name' => 'サンプル商品5'
                ],
                (object)[
                    'image_path' => 'default-item.png',
                    'name' => 'サンプル商品6'
                ],
                (object)[
                    'image_path' => 'default-item.png',
                    'name' => 'サンプル商品7'
                ],
                (object)[
                    'image_path' => 'default-item.png',
                    'name' => 'サンプル商品8'
                ],

            ];
            @endphp

            <div class="grid-container">
                @foreach ($items as $item)
                <div class="item">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                    </div>
                    <div class="item-name">{{ $item->name }}</div>
                </div>
                @endforeach
            </div>
        </div>

</div>
@endsection