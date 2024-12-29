@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}" />
@endsection

@section('content')

<h1>商品の出品</h1>
        <form action="#" method="post" enctype="multipart/form-data">
            <section>
                <label for="product-image">商品画像</label>
                <div class="image-upload">
                    <label for="product-image" class="custom-file-label">画像を選択する</label>
                    <input type="file" id="product-image" name="product_image" class="custom-file-input">
                </div>
            </section>



            <section>
                <h2>商品の詳細</h2>
                <hr>
                <label for="category">カテゴリー</label>
                <div class="categories">
                    <input type="radio" id="fashion" name="category" class="category-radio">
                    <label for="fashion" class="category-label">ファッション</label>

                    <input type="radio" id="electronics" name="category" class="category-radio">
                    <label for="electronics" class="category-label">家電</label>

                    <input type="radio" id="interior" name="category" class="category-radio">
                    <label for="interior" class="category-label">インテリア</label>

                    <input type="radio" id="ladies" name="category" class="category-radio">
                    <label for="ladies" class="category-label">レディース</label>

                    <input type="radio" id="mens" name="category" class="category-radio">
                    <label for="mens" class="category-label">メンズ</label>

                    <input type="radio" id="cosmetics" name="category" class="category-radio">
                    <label for="cosmetics" class="category-label">コスメ</label>

                    <input type="radio" id="books" name="category" class="category-radio">
                    <label for="books" class="category-label">本</label>

                    <input type="radio" id="games" name="category" class="category-radio">
                    <label for="games" class="category-label">ゲーム</label>

                    <input type="radio" id="sports" name="category" class="category-radio">
                    <label for="sports" class="category-label">スポーツ</label>

                    <input type="radio" id="kitchen" name="category" class="category-radio">
                    <label for="kitchen" class="category-label">キッチン</label>

                    <input type="radio" id="handmade" name="category" class="category-radio">
                    <label for="handmade" class="category-label">ハンドメイド</label>

                    <input type="radio" id="accessories" name="category" class="category-radio">
                    <label for="accessories" class="category-label">アクセサリー</label>

                    <input type="radio" id="toys" name="category" class="category-radio">
                    <label for="toys" class="category-label">おもちゃ</label>

                    <input type="radio" id="baby_kids" name="category" class="category-radio">
                    <label for="baby_kids" class="category-label">ベビー・キッズ</label>
                </div>

            </section>

            <section>
                <label for="status">商品の状態</label>
                <div class="custom-select-wrapper">
                    <select id="status" name="status" class="custom-select">
                        <option value="" disabled selected>選択してください</option>
                        <option value="new">新品</option>
                        <option value="used">中古</option>
                    </select>
                </div>
            </section>

            <section>
                <h2>商品名と説明</h2>
                <hr>
                <label for="product-name">商品名</label>
                <input type="text" id="product-name" name="product_name">

                <label for="description">商品の説明</label>
                <textarea id="description" name="description"></textarea>

                <label for="price">販売価格</label>
                <input type="text" id="price" name="price" placeholder="¥">
            </section>

            <button type="submit" class="submit-button">出品する</button>
        </form>
@endsection