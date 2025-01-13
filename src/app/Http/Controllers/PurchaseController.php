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

        // 商品IDをセッションに保存
        session(['item_id' => $item_id]);

        return view('purchase', compact('item', 'user', 'paymentMethod'));
    }

    public function store(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // 商品が購入可能か確認
        if ($item->status === 'sold') {
            return redirect()->route('mypage');
        }

        // Stripe支払いの確認（Stripe未実装の場合は仮の条件を使う）
        $paymentIntentId = $request->input('payment_intent_id');
        $paymentSucceeded = true; // Stripe未実装の仮フラグ（後で置き換える）

        // Stripeが有効になった場合に以下を使用
        // Stripe::setApiKey(env('STRIPE_SECRET'));
        // $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
        // $paymentSucceeded = $paymentIntent->status === 'succeeded';

        if ($paymentSucceeded) {
            // 購入情報の保存
            Purchase::create([
                'item_id' => $item->id,
                'buyer_id' => Auth::id(),
                'address' => Auth::user()->address,
                'building' => Auth::user()->building,
                'postal_code' => Auth::user()->postal_code,
                'payment_method' => $request->input('payment_method', 'card'),
            ]);

            // 商品のステータスを「sold」に更新
            $item->update(['status' => 'sold']);

            return redirect()->route('mypage', ['tab' => 'purchase'])->with('success', '購入が完了しました');
        }

        // 支払いが失敗した場合
        return back()->withErrors(['message' => '支払いが完了しませんでした']);

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
