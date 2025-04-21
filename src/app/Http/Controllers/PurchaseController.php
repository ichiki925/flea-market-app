<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\SoldItem;
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
            return redirect()->route('mypage.profile')
                ->withErrors(['address' => '配送先情報が未登録です。プロフィールを設定してください。']);
        }

        $item = Item::findOrFail($item_id);

        if ($item->status === 'sold') {
            return redirect()->route('mypage');
        }


        $paymentMethod = $request->input('payment_method');

        if ($paymentMethod === 'カード支払い') {
            $stripePaymentMethod = 'card';
        } elseif ($paymentMethod === 'コンビニ払い') {
            $stripePaymentMethod = 'konbini';
        } else {
            return back()->withErrors(['payment_method' => '支払い方法を選択してください。']);
        }

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            $sessionData = [
                'payment_method_types' => [$stripePaymentMethod],
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
                'success_url' => route('payment.success', ['item_id' => $item->id]),
                'cancel_url' => route('payment.cancel', ['item_id' => $item->id]),
            ];


            if ($stripePaymentMethod === 'konbini') {
                $sessionData['payment_method_options'] = [
                    'konbini' => [
                        'expires_after_days' => 3,
                    ],
                ];
            }


            $session = StripeSession::create($sessionData);
            return redirect($session->url);

        } catch (\Exception $e) {
            return back()->withErrors(['message' => '決済セッションの作成中にエラーが発生しました: ' . $e->getMessage()]);
        }
    }


    public function paymentSuccess(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();


        if ($item->status === 'sold' || SoldItem::where('item_id', $item->id)->exists()) {
            return redirect()->route('mypage')->with('error', 'この商品は既に売れています。');
        }


        SoldItem::create([
            'item_id' => $item->id,
            'user_id' => $item->user_id,
            'buyer_id' => $user->id,
            'sending_postcode' => $user->profile->postcode,
            'sending_address' => $user->profile->address,
            'sending_building' => $user->profile->building,
            'payment_method' => 'card',
        ]);


        $item->update(['status' => 'trading']);

        return redirect()->route('mypage', ['tab' => 'purchase'])->with('success', '購入が完了しました！');
    }

    public function paymentCancel(Request $request, $item_id)
    {
        return redirect()->route('item.detail', ['id' => $item_id])->withErrors(['message' => '支払いがキャンセルされました。']);
    }


}
