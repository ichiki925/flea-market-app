@extends($layout)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

    <main class="main">
        <div class="tabs-container">
            <div class="tabs">
                <a href="{{ route('index', ['search' => request('search')]) }}"
                class="tab-link {{ request()->get('tab') !== 'mylist' ? 'active' : '' }}">
                    おすすめ
                </a>
                <a href="{{ route('index', ['tab' => 'mylist', 'search' => request('search')]) }}"
                class="tab-link {{ request()->get('tab') === 'mylist' ? 'active' : '' }}">
                    マイリスト
                </a>
            </div>
        </div>

        <div class="item-list grid-container">
            @if(request('tab') === 'mylist')
            @if(auth()->check())
            @foreach ($items as $item)
                <div class="item">
                    <a href="{{ route('item.detail', ['id' => $item->id]) }}">
                        <div class="item-image">
                            <img src="{{ asset('storage/' . $item->img_url) }}" alt="商品画像：{{ $item->name }}" loading="lazy">
                            @if($item->status === 'sold')
                                <div class="item-status">Sold</div>
                            @endif
                        </div>

                    <div class="item-info">
                        <div class="item-name">{{ $item->name }}</div>
                    </div>
                </a>
                </div>
            @endforeach
            @else
            <p>ログインしていないため、マイリストを表示できません。</p>
                @endif
            @else

                @foreach ($items as $item)
                    <div class="item">
                        <a href="{{ route('item.detail', ['id' => $item->id]) }}">
                            <div class="item-image">
                                <img src="{{ asset('storage/' . $item->img_url) }}" alt="商品画像：{{ $item->name }}" loading="lazy">
                                @if($item->status === 'sold')
                                    <div class="item-status">Sold</div>
                                @endif
                            </div>

                        <div class="item-info">
                            <div class="item-name">{{ $item->name }}</div>
                        </div>
                    </a>
                    </div>
                @endforeach
            @endif

        </div>
    </main>
@endsection