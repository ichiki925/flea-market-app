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
        $user = auth()->user(); // ログインユーザー（購入者）
        $item = Item::findOrFail($item_id);

        // バリデーション
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // レビュー保存
        $review = new Review();
        $review->reviewer_id = $user->id;
        $review->reviewee_id = $item->user_id; // 出品者
        $review->item_id = $item->id;
        $review->rating = $request->input('rating');
        $review->save();

        // アイテムを「取引完了」に変更
        $item->status = 'sold';
        $item->save();

        // === ★★★ 追加：出品者に通知メールを送信 ===
        $seller = $item->user;
        Mail::to($seller->email)->send(new TransactionCompleted($item, $user));

        // === ★★★ リダイレクトをおすすめタブに ===
        return redirect()->route('mylist', ['tab' => 'index'])->with('success', '評価を送信しました！');
    }

}
