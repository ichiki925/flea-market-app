<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $items = Item::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->get();

        return view('index', compact('items'));
    }

    public function mylist(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $tab = $request->input('tab', 'mylist'); // デフォルトは「マイリスト」

        if ($tab === 'mylist') {
            // いいねした商品を取得
            $items = Item::whereHas('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })->get();
        } else {
            // 全商品を取得
            $items = Item::when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })->get();
        }

        return view('mylist', compact('items', 'tab'));
    }

    public function show($id)
    {
        $item = Item::with(['categories', 'condition'])->findOrFail($id);

        $viewName = auth()->check() ? 'item_detail' : 'item_detail_guest';

        return view($viewName, compact('item'));

    }
}