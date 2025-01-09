@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
@endsection

@section('content')
<div class="profile-container">
    <h2>プロフィール設定</h2>
    <form action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
        @csrf
        @method('PUT')

        <div class="profile-image">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="profile-preview">
            @endif
            <label for="profile_image" class="file-label">
                画像を選択する
                <input type="file" name="profile_image" id="profile_image" accept=".jpeg,.png">
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
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            @error('postal_code')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}">
            @error('address')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $user->building) }}">
            @error('building')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-submit">更新する</button>
    </form>
</div>
@endsection