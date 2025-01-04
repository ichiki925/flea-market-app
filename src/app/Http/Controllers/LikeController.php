<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggleLike($itemId)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login'); // 未ログインの場合はログインページにリダイレクト
        }

        // 既存のいいねを確認
        $like = Like::where('item_id', $itemId)->where('user_id', $user->id)->first();

        if ($like) {
            // いいねを解除
            $like->delete();
        } else {
            // いいねを登録
            Like::create([
                'item_id' => $itemId,
                'user_id' => $user->id,
            ]);
        }

        return redirect()->back(); // 元のページにリダイレクト

    }


}
