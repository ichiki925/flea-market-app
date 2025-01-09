<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            $items = Item::whereHas('purchases', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
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
            'user' => null,
            'isEdit' => false,
        ]);
    }

    // プロフィール新規保存処理
    public function storeProfile(ProfileRequest $request)
    {
        // 新しいユーザー作成
        $user = new User($request->validated());

        // プロフィール画像の保存
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        $user->save();

        return redirect()->route('mypage.profile')->with('success', 'プロフィールが登録されました。');
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
    public function updateProfile(AddressRequest $request)
    {
        $user = auth()->user();

        $validatedData = $request->validated();

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        // その他のフィールドを更新
        $user->update($validatedData);

        return redirect()->route('mypage.profile')->with('success', 'プロフィールが更新されました。');
    }
}
