<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->input('tab', 'sell');

        if ($tab === 'sell') {
            // 出品した商品
            $items = Item::where('user_id', $user->id)->get();
        } else {
            // 購入した商品
            $items = $user->purchases()->with('item')->get()->map->item; // リレーションからアイテムを取得
        }

        return view('mypage', compact('items', 'tab'));
    }

    public function purchases()
    {
        $user = auth()->user();

        // 購入した商品を取得するロジック
        $items = Item::whereHas('purchases', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('mypage', [
            'items' => $items,
            'tab' => 'purchase', // 購入商品のタブを選択状態にする
        ]);
    }


    // プロフィール新規作成画面
    public function createProfile()
    {
        return view('profile', [
            'user' => auth()->user(),
            'isEdit' => false,
        ]);
    }

    public function storeProfile(ProfileRequest $request)
    {

        $validated = $request->validated();

        $user = auth()->user();

        $user->fill($validated);

        // プロフィール画像がアップロードされた場合
        if ($request->hasFile('profile_image')) {
            // 新しい画像を保存
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path; // 画像パスをユーザーに直接設定
        }


        // ユーザー情報を保存
        $user->save();
        // ユーザー情報を更新
        Auth::setUser($user);
        // プロフィール編集画面にリダイレクト
        return redirect()->route('mylist');
    }

    // プロフィール編集画面
    public function editProfile()
    {
        $user = auth()->user(); // 現在ログイン中のユーザー情報取得

        return view('profile', [
            'user' => $user,
            'isEdit' => true, // 編集モード
        ]);
    }

    // プロフィール更新処理
    public function updateProfile(ProfileRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // プロフィール画像がアップロードされた場合のみ処理
        if ($request->hasFile('profile_image')) {
            // 古い画像の削除
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            // 新しい画像を保存
            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $path; // validatedデータに画像パスを追加
        } else {
            // プロフィール画像がアップロードされていない場合は既存の画像を保持
            $validated['profile_image'] = $user->profile_image;
        }


        // その他のフィールドを更新
        $user->update($validated);

        // ユーザー情報を更新
        Auth::setUser($user);

        return redirect()->route('mypage');
    }

    public function editAddress(Request $request)
    {
        $user = Auth::user();
        $item_id = $request->query('item_id'); // アイテムIDを取得

        return view('address', compact('user','item_id'));
    }

    public function updateAddress(AddressRequest $request)
    {

        $user = Auth::user();

        $validated = $request->validated();

        $user->update($validated);

        $item_id = $request->input('item_id');

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
