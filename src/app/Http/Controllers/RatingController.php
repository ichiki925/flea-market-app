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
        $user = auth()->user();
        $item = Item::findOrFail($item_id);

        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        $review = new Review();
        $review->reviewer_id = $user->id;
        $review->reviewee_id = $item->user_id;
        $review->item_id = $item->id;
        $review->rating = $request->input('rating');
        $review->save();


        $item->status = 'sold';
        $item->save();


        $seller = $item->user;
        Mail::to($seller->email)->send(new TransactionCompleted($item, $user));


        return redirect()->route('mylist', ['tab' => 'index'])->with('success', '評価を送信しました！');
    }

}
