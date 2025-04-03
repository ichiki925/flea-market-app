@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
@endsection

@section('content')
<div class="profile-container">
    <h2>プロフィール設定</h2>
    <form action="{{ $isEdit ? route('mypage.update') : route('mypage.store') }}" method="POST" enctype="multipart/form-data" class="profile-form">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="profile-image">
                <img id="profile-preview" class="profile-preview" src="{{ $user && $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-placeholder.png') }}" alt="プロフィール画像">
            <label for="profile_image" class="file-label">
                画像を選択する
                <input type="file" name="profile_image" id="profile_image" accept=".jpeg,.jpg,.png">
            </label>
        </div>


        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
            @error('name')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="postcode">郵便番号</label>
            <input type="text" id="postcode" name="postcode" value="{{ old('postcode', optional($user->profile)->postcode) }}">
            @error('postcode')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" value="{{ old('address', optional($user->profile)->address) }}">
            @error('address')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" value="{{ old('building', optional($user->profile)->building) }}">
            @error('building')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-submit">更新する</button>
    </form>
</div>

<script>
    document.getElementById('profile_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const preview = document.getElementById('profile-preview');
            if (preview) {
                preview.src = URL.createObjectURL(file); // 選択された画像をプレビュー
                preview.style.display = 'block'; // プレビューを表示
            }
        }
    });
</script>

@endsection