@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/mylist.css') }}" />
@endsection



@section('content')
<main class="main">
    <div class="tabs-container">
        <div class="tabs">
            <a href="{{ route('index', ['search' => request('search')]) }}"
            class="tab-link {{ request()->routeIs('index') ? 'active' : '' }}">
                おすすめ
            </a>
            <a href="{{ route('mylist', ['search' => request('search')]) }}"
            class="tab-link {{ request()->routeIs('mylist') ? 'active' : '' }}">
                マイリスト
            </a>
        </div>
    </div>

    <div class="item-list grid-container">
        @foreach ($items as $item)
        <div class="item">
            @if($item->status !== 'sold')
                <a href="{{ route('item.detail.guest', ['id' => $item->id]) }}">
                    <div class="item-image">
                        <img src="{{ $item->item_image ? asset('storage/' . rawurlencode($item->item_image)) : asset('images/default.png') }}" alt="{{ $item->name }}">
                    </div>
                    <div class="item-name">{{ $item->name }}</div>
                </a>
            @else
                <div class="item-image">
                    <img src="{{ $item->item_image ? asset('storage/' . rawurlencode($item->item_image)) : asset('images/default.png') }}" alt="{{ $item->name }}">
                </div>
                <div class="item-name">{{ $item->name }}</div>
                <div class="item-status">Sold</div>
            @endif
        </div>
        @endforeach


    </div>
</main>

@endsection
