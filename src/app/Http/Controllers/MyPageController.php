<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->input('tab', 'sell'); // デフォルトは「出品した商品」

        if ($tab === 'sell') {
            // 出品した商品
            $items = Item::where('user_id', $user->id)->get();
        } else {
            // 購入した商品
            $items = Item::whereHas('purchases', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        }

        return view('mypage', compact('items', 'tab'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }
}
