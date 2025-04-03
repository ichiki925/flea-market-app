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

        session(['item_id' => $item_id]);

        return view('purchase', compact('item', 'user', 'paymentMethod'));
    }

    public function store(Request $request, $item_id)
    {
        $user = auth()->user();

        if (
            !$user->profile ||
            !$user->profile->postcode ||
            !$user->profile->address
        ) {
            return redirect()->route('mypage.editProfile')
                ->withErrors(['address' => '配送先情報が未登録です。プロフィールを設定してください。']);
        }

        $item = Item::findOrFail($item_id);

        if ($item->status === 'sold') {
            return redirect()->route('mypage');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => (int)$item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['item_id' => $item_id]),
                'cancel_url' => route('payment.cancel', ['item_id' => $item_id]),
            ]);

            // 商品のステータスを`sold`に更新
            $item->update(['status' => 'sold']);

            // 購入データを保存
            Purchase::create([
                'item_id' => $item->id,
                'buyer_id' => auth()->id(),
                'address' => $request->address,
                'building' => $request->building ?? '',
                'postcode' => $request->postcode,
                'payment_method' => 'card',
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            return back()->withErrors(['message' => '決済セッションの作成中にエラーが発生しました: ' . $e->getMessage()]);
        }
    }

    public function paymentSuccess(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        Purchase::create([
            'item_id' => $item->id,
            'buyer_id' => Auth::id(),
            'address' => Auth::user()->address,
            'building' => Auth::user()->building,
            'postcode' => Auth::user()->postcode,
            'payment_method' => 'card',
        ]);

        $item->update(['status' => 'sold']);

        return redirect()->route('mypage', ['tab' => 'purchase'])->with('success', '購入が完了しました！');
    }

    public function paymentCancel(Request $request, $item_id)
    {
        return redirect()->route('item.detail', ['id' => $item_id])->withErrors(['message' => '支払いがキャンセルされました。']);
    }


}
