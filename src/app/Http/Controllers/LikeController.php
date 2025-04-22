<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggleLike($itemId)
    {
        $user = auth()->user();

        // 明示的に where 条件で削除（$like->delete()は使わない）
        $existing = Like::where('item_id', $itemId)
                        ->where('user_id', $user->id)
                        ->first();

        if ($existing) {
            Like::where('item_id', $itemId)
                ->where('user_id', $user->id)
                ->delete(); // ← これなら OK！

            if (request()->expectsJson()) {
                return response()->json(['status' => 'unliked'], 200);
            }

            return redirect()->back()->with('success', 'いいねを解除しました');
        } else {
            Like::create([
                'item_id' => $itemId,
                'user_id' => $user->id,
            ]);

            if (request()->expectsJson()) {
                return response()->json(['status' => 'liked'], 200);
            }

            return redirect()->back()->with('success', 'いいねしました');
        }
    }


}
