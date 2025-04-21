@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/mylist.css') }}" />
@endsection



@section('content')
<main class="main">
    <div class="tabs-container">
        <div class="tabs">
            @php
                $currentTab = request('tab') ?? (request('search') ? 'index' : 'mylist');
            @endphp

            <a href="{{ route('mylist', ['tab' => 'index', 'search' => request('search')]) }}"
            class="tab-link {{ $currentTab === 'index' ? 'active' : '' }}">
                おすすめ
            </a>
            <a href="{{ route('mylist', ['tab' => 'mylist', 'search' => request('search')]) }}"
            class="tab-link {{ $currentTab === 'mylist' ? 'active' : '' }}">
                マイリスト
            </a>
        </div>
    </div>


    <div class="item-list grid-container">
        @if(auth()->check() && $items->isNotEmpty())
            @foreach ($items as $item)
            <div class="item">
                <a href="{{ route('item.detail', ['id' => $item->id]) }}">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" loading="lazy">
                        @if(in_array($item->status, ['sold', 'trading']))
                            <div class="item-status">Sold</div>
                        @endif
                    </div>
                    <div class="item-name">{{ $item->name }}</div>
                </a>
            </div>
            @endforeach
        @endif
    </div>
</main>

@endsection
