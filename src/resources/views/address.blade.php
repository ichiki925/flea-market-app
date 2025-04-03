@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}" />
@endsection

@section('content')
<div class="address-container">
    <h2>住所の変更</h2>
    <form action="{{ route('mypage.updateAddress') }}" method="POST" class="address-form">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item_id }}">
        <div class="form-group">
            <label for="postcode">郵便番号</label>
            <input type="text" id="postcode" name="postcode" value="{{ old('postcode', $user->profile->postcode ?? '') }}">
            @error('postcode')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $user->profile->address ?? '') }}">
            @error('address')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $user->profile->building ?? '') }}">
            @error('building')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn-submit">更新する</button>
    </form>
</div>
@endsection