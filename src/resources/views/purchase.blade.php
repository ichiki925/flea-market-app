@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
@endsection

@section('content')
<div class="purchase-container">
    <div class="left-section">
        <div class="product-detail">
            <div class="product-image">
                <img src="{{ asset('storage/' . $item->img_url) }}" alt="商品画像：{{ $item->name }}" class="product-image-img" loading="lazy">
            </div>
            <div class="product-info">
                <h2>{{ $item->name }}</h2>
                <p class="product-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>
        <div class="payment-method">
            <h3>支払い方法</h3>
            <div class="custom-select-wrapper">
                <form action="{{ route('purchase.show', ['item_id' => $item->id]) }}" method="GET">
                    <select name="payment_method" onchange="this.form.submit()">
                        <option value="">選択してください</option>
                        <option value="コンビニ払い" {{ request('payment_method') === 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                        <option value="カード支払い" {{ request('payment_method') === 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
                    </select>
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                </form>
            </div>
        </div>
        <div class="shipping-address">
            <div class="shipping-header">
                <h3>配送先</h3>
                <a href="{{ route('mypage.editAddress', ['item_id' => $item->id]) }}" class="change-address">変更する</a>
            </div>
            <p>〒 {{ $user->profile->postcode }}</p>
            <p>{{ $user->profile->address }}</p>
            <p>{{ $user->profile->building }}</p>

            @error('address')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="right-section">
        <div class="payment-summary">
            <table>
                <tr>
                    <th>商品代金</th>
                    <td>¥{{ number_format($item->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td>{{ request('payment_method') ?: '未選択' }}</td>
                </tr>
            </table>
        </div>
        <form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="POST">
            @csrf
        <button type="submit" class="btn-purchase">購入する</button>
        </form>
    </div>
</div>
@endsection