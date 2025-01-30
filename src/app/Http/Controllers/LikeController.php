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
        $like = Like::where('item_id', $itemId)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['status' => 'unliked'], 200);
        } else {
            Like::create([
                'item_id' => $itemId,
                'user_id' => $user->id,
            ]);
            return response()->json(['status' => 'liked'], 200);
        }

    }


}
