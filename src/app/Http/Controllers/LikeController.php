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


        $like = \App\Models\Like::where('item_id', $itemId)
                    ->where('user_id', $user->id)
                    ->first();

        if ($like) {

            \App\Models\Like::where('item_id', $itemId)
                ->where('user_id', $user->id)
                ->delete();

            return redirect()->back()->with('success', 'いいねを解除しました');
        } else {
            \App\Models\Like::create([
                'item_id' => $itemId,
                'user_id' => $user->id,
            ]);

            return redirect()->back()->with('success', 'いいねしました');
        }
    }


}
