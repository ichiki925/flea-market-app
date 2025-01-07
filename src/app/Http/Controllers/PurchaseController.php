<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    public function show(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $paymentMethod = $request->input('payment_method');

        return view('purchase', compact('item', 'user', 'paymentMethod'));
    }

    public function store(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // 商品が購入可能か確認
        if ($item->status === 'sold') {
            return redirect()->route('mypage');
        }

        // Stripe支払いの確認をここに追加
        $paymentIntentId = $request->input('payment_intent_id');
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

        if ($paymentIntent->status === 'succeeded') {
            // 購入情報を保存
            Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
            ]);

            // 商品の状態を更新
            $item->update(['status' => 'sold']);

            return redirect()->route('mypage');
        }
        return back()->withErrors(['message' => 'Payment failed']);
    }

    public function processPayment(Request $request)
        {
            // Stripeの秘密キーを設定
            Stripe::setApiKey(env('STRIPE_SECRET'));

            try {
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $request->input('amount'), // 金額
                    'currency' => 'jpy', // 通貨
                    'payment_method' => $request->input('payment_method_id'), // 支払い方法ID
                    'confirmation_method' => 'manual', // 手動確認
                    'confirm' => true, // 即時確認
                ]);

                return response()->json(['clientSecret' => $paymentIntent->client_secret]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

}
