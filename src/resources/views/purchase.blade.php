@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
@endsection

@section('content')
<div class="purchase-container">
    <div class="left-section">
        <div class="product-detail">
            <div class="product-image">
                商品画像
            </div>
            <div class="product-info">
                <h2>商品名</h2>
                <p class="product-price">¥47,000</p>
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
            <p>〒 XXX-YYYY</p>
            <p>ここには住所と建物が入ります</p>
        </div>
    </div>

    <div class="right-section">
        <div class="payment-summary">
            <table>
                <tr>
                    <th>商品代金</th>
                    <td>¥47,000</td>
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