@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}" />
@endsection

@section('content')
<main>
    <h1>商品の出品</h1>
        <form action="{{ route('sell.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <section>
                <label for="item-image">商品画像</label>
                <div class="image-upload">
                    <label for="item-image" class="custom-file-label">画像を選択する</label>
                    <input type="file" id="item-image" name="img_url" class="custom-file-input">
                    <img class="preview-image" src="{{ isset($item->img_url) ? asset('storage/' . $item->img_url) : asset('images/default.png') }}" alt="商品画像">

                </div>
                @error('img_url')
                <p class="error">{{ $message }}</p>
                @enderror
            </section>



            <section>
                <h2>商品の詳細</h2>
                <hr>
                <label for="category">カテゴリー</label>
                <div class="categories">
                    @foreach ($categories as $category)
                        <input type="checkbox" id="category-{{ $category->id }}" name="item_categories[]" value="{{ $category->id }}" class="category-checkbox"
                            {{ is_array(old('item_categories')) && in_array($category->id, old('item_categories')) ? 'checked' : '' }}>
                        <label for="category-{{ $category->id }}" class="category-label">{{ $category->category }}</label>
                    @endforeach
                </div>
                @error('item_categories')
                <p class="error">{{ $message }}</p>
                @enderror
            </section>

            <section>
                <label for="status">商品の状態</label>
                <div class="custom-select-wrapper">
                    <select id="status" name="condition" class="custom-select">
                        <option value="" disabled selected>選択してください</option>
                        @foreach ($conditions as $condition)
                            <option value="{{ $condition->id }}">{{ $condition->condition }}</option>
                        @endforeach
                    </select>
                    @error('condition')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section>
                <h2>商品名と説明</h2>
                <hr>
                <label for="product-name">商品名</label>
                <input type="text" id="product-name" name="name" value="{{ old('name') }}">
                @error('name')
                <p class="error">{{ $message }}</p>
                @enderror

                <label for="description">商品の説明</label>
                <textarea id="description" name="description">{{ old('description') }}</textarea>
                @error('description')
                <p class="error">{{ $message }}</p>
                @enderror

                <label for="price">販売価格</label>
                <input type="text" id="price" name="price" value="{{ old('price') }}" placeholder="¥">
                @error('price')
                <p class="error">{{ $message }}</p>
                @enderror
            </section>

            <button type="submit" class="submit-button">出品する</button>
        </form>
</main>

<script>
    document.getElementById('item-image').addEventListener('change', function (event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.querySelector('.preview-image');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });
</script>


@endsection