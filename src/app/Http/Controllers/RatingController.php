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


        if ($item->user_id === $user->id) {
            $soldItem = $item->soldItems()->first();
            $reviewee_id = $soldItem ? $soldItem->buyer_id : null;
        } else {
            $reviewee_id = $item->user_id;
        }

        if (!$reviewee_id) {
            return back()->withErrors(['レビュー送信失敗：購入者情報が見つかりません']);
        }

        Review::create([
            'reviewer_id' => $user->id,
            'reviewee_id' => $reviewee_id,
            'item_id' => $item->id,
            'rating' => $request->input('rating'),
        ]);

        $item->status = 'sold';
        $item->save();

        $seller = $item->user;
        Mail::to($seller->email)->send(new TransactionCompleted($item, $user));

        return redirect()->route('mylist', ['tab' => 'index'])->with('success', '評価を送信しました！');
    }


}
