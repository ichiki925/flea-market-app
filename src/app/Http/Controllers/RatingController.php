<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCompleted;

class RatingController extends Controller
{
    public function submitReview(Request $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::with('soldItems')->findOrFail($item_id);

        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // 相手ユーザーの取得
        if ($item->user_id === $user->id) {
            // 出品者が評価
            $soldItem = $item->soldItems()->first();
            $reviewee_id = $soldItem ? $soldItem->buyer_id : null;
        } else {
            // 購入者が評価
            $reviewee_id = $item->user_id;
        }

        if (!$reviewee_id) {
            return back()->withErrors(['レビュー送信失敗：相手ユーザーが見つかりません']);
        }

        // 重複防止（同じユーザーが同じ商品に2回評価しない）
        $alreadyReviewed = Review::where([
            'reviewer_id' => $user->id,
            'reviewee_id' => $reviewee_id,
            'item_id'     => $item->id,
        ])->exists();

        if (!$alreadyReviewed) {
            Review::create([
                'reviewer_id' => $user->id,
                'reviewee_id' => $reviewee_id,
                'item_id'     => $item->id,
                'rating'      => $request->input('rating'),
            ]);
        }

        // ✅ ここがポイント！ → 両者が評価したら sold に変更
        $bothReviewed = Review::where('item_id', $item->id)
            ->distinct('reviewer_id')
            ->count('reviewer_id') >= 2;

        if ($bothReviewed) {
            $item->status = 'sold';
            $item->save();

            // 通知メール（例：出品者に）
            $seller = $item->user;
            Mail::to($seller->email)->send(new TransactionCompleted($item, $user));
        }

        return redirect()->route('mylist', ['tab' => 'index'])->with('success', '評価を送信しました！');
    }

}
