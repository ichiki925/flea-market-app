<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Like;
use App\Models\Item;

class LikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function toggleLike($itemId)
    {
        $user = auth()->user();
        $like = Like::where('item_id', $itemId)->where('user_id', $user->id)->first();

        if ($like) {
            // いいねが存在する場合は削除
            $like->delete();
            return response()->json(['status' => 'unliked']);
        } else {
            // いいねが存在しない場合は作成
            Like::create([
                'item_id' => $itemId,
                'user_id' => $user->id,
            ]);
            return response()->json(['status' => 'liked']);
        }
    }

    public function guestLike($itemId)
    {
        return response()->json(['message' => 'Please log in to like items'], 401);
    }


}
