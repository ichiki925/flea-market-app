@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
@endsection

@section('content')
<div class="purchase-container">
    <div class="left-section">
        <div class="product-detail">
            <div class="product-image">
                <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}" class="product-image-img">
            </div>
            <div class="product-info">
                <h2>{{ $item->name }}</h2>
                <p class="product-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>
        <div class="payment-method">
            <h3>支払い方法</h3>
            <div class="custom-select-wrapper">
                <select>
                    <option>選択してください</option>
                    <option>コンビニ払い</option>
                    <option>カード支払い</option>
                </select>
            </div>
        </div>
        <div class="shipping-address">
            <div class="shipping-header">
                <h3>配送先</h3>
                <a href="/address" class="change-address">変更する</a>
            </div>
            <p>〒 {{ $user->postal_code }}</p>
            <p>{{ $user->address }}</p>
            <p>{{ $user->building }}</p>
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
                    <td>コンビニ払い</td>
                </tr>
            </table>
        </div>
        <button class="btn-purchase">購入する</button>
    </div>
</div>
@endsection