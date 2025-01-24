@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/mylist.css') }}" />
@endsection



@section('content')
<main class="main">
    <div class="tabs-container">
        <div class="tabs">
            <a href="{{ route('mylist', ['tab' => 'index', 'search' => request('search')]) }}"
            class="tab-link {{ request('tab') === 'index' ? 'active' : '' }}">
                おすすめ
            </a>
            <a href="{{ route('mylist', ['tab' => 'mylist', 'search' => request('search')]) }}"
            class="tab-link {{ request('tab', 'mylist') === 'mylist' ? 'active' : '' }}">
                マイリスト
            </a>
        </div>
    </div>

    <div class="item-list grid-container">
        @if(auth()->check() && $items->isNotEmpty())
            @foreach ($items as $item)
            <div class="item">
                @if($item->status !== 'sold')
                    <a href="{{ route('item.detail', ['id' => $item->id]) }}">
                        <div class="item-image">
                            <img src="{{ Str::startsWith($item->item_image, 'images/') ? asset($item->item_image) : asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}">
                        </div>
                        <div class="item-name">{{ $item->name }}</div>
                    </a>
                @else
                    <div class="item-image">
                        <img src="{{ Str::startsWith($item->item_image, 'images/') ? asset($item->item_image) : asset('storage/' . $item->item_image) }}" alt="{{ $item->name }}">
                    </div>
                    <div class="item-name">{{ $item->name }}</div>
                    <div class="item-status">Sold</div>
                @endif
            </div>
            @endforeach
        @endif


    </div>
</main>

@endsection
