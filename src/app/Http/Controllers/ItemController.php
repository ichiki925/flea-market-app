<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');

        $items = Item::when($user, function ($query) use ($user) {
                            return $query->where('user_id', '!=', $user->id);
                        })
                        ->when($search, function ($query, $search) {
                            return $query->where('name', 'like', '%' . $search . '%'); // 部分一致
                        })
                        ->get();

        return view('index', compact('items'));
    }

    // 認証済みユーザー用のマイリスト
    public function mylist(Request $request)
    {
        // ログイン中のユーザーが登録した商品のみ取得
        $user = auth()->user();
        $items = $user->mylistItems()->get();
        $search = $request->input('search'); // 検索キーワードを取得

        // 部分一致検索
        $items = $user->mylistItems()
                        ->when($search, function ($query, $search) {
                            return $query->where('name', 'like', '%' . $search . '%'); // 部分一致
                        })
                        ->get();

        return view('mylist', compact('items'));
    }

    public function showGuest($id)
    {
        // 商品データを取得
        $item = Item::findOrFail($id);

        // ビューにデータを渡して表示
        return view('item_detail_guest', compact('item'));
    }


}
