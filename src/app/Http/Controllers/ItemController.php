<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    // 未認証ユーザー用の商品一覧
    public function index()
    {
        // 商品一覧データを取得
        $items = Item::paginate(8);

        return view('index', compact('items'));
    }

    // 認証済みユーザー用のマイリスト
    public function mylist()
    {
        // ログイン中のユーザーが登録した商品のみ取得
        $user = auth()->user();
        $mylistItems = $user->mylistItems; // リレーションを使った例

        return view('mylist', compact('mylistItems'));
    }


}
