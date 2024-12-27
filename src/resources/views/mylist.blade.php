@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/mylist.css') }}" />
@endsection



@section('content')

    <div class="tabs-container">
        <div class="tabs">
            <a href="/" class="tab-link active">おすすめ</a>
            <a href="/?tab=mylist" class="tab-link">マイリスト</a>
        </div>
    </div>

    <div class="item-list">
        @foreach ($items as $item)
        <div class="item">
            <div class="item-image">
                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
            </div>
            <div class="item-name">{{ $item->name }}</div>
        </div>
        @endforeach

        <div>
            {{ $items->links() }}
        </div>
    </div>
@endsection
