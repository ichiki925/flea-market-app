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
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code ?? '') }}">
            @error('postal_code')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}">
            @error('address')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $user->building ?? '') }}">
            @error('building')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn-submit">更新する</button>
    </form>
</div>
@endsection