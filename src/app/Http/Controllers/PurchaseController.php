<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

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

        // Stripeの秘密キーを設定
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Stripe Checkoutセッションを作成
            $session = StripeSession::create([
                'payment_method_types' => ['card'], // 支払い方法
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy', // 通貨
                        'product_data' => [
                            'name' => $item->name, // 商品名
                        ],
                        'unit_amount' => (int)$item->price, // 商品価格（単位：最小通貨単位）
                    ],
                    'quantity' => 1, // 購入数
                ]],
                'mode' => 'payment', // 支払いモード
                'success_url' => route('payment.success', ['item_id' => $item_id]), // 決済成功時のURL
                'cancel_url' => route('payment.cancel', ['item_id' => $item_id]),  // キャンセル時のURL
            ]);

            // Stripeの決済ページへリダイレクト
            return redirect($session->url);

        } catch (\Exception $e) {
            // エラーハンドリング
            return back()->withErrors(['message' => '決済セッションの作成中にエラーが発生しました: ' . $e->getMessage()]);
        }
    }

    public function paymentSuccess(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // 購入情報の保存
        Purchase::create([
            'item_id' => $item->id,
            'buyer_id' => Auth::id(),
            'address' => Auth::user()->address,
            'building' => Auth::user()->building,
            'postal_code' => Auth::user()->postal_code,
            'payment_method' => 'card',
        ]);

        // 商品のステータスを「sold」に更新
        $item->update(['status' => 'sold']);

        return redirect()->route('mypage', ['tab' => 'purchase'])->with('success', '購入が完了しました！');
    }

    public function paymentCancel(Request $request, $item_id)
    {
        return redirect()->route('item.detail', ['id' => $item_id])->withErrors(['message' => '支払いがキャンセルされました。']);
    }


}
