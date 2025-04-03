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
            $items = Item::where('user_id', $user->id)->get();
        } else {
            $items = $user->purchases()->with('item')->get()->pluck('item');
        }

        return view('mypage', compact('items', 'tab'));
    }

    public function purchases()
    {
        $user = auth()->user();

        $items = $user->purchases()->with('item')->get()->pluck('item');

        return view('mypage', [
            'items' => $items,
            'tab' => 'purchase',
        ]);
    }



    public function createProfile()
    {
        return view('profile', [
            'user' => auth()->user(),
            'isEdit' => false,
        ]);
    }

    public function storeProfile(ProfileRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // ユーザー名などはusersテーブルに
        $user->name = $validated['name'];
        $user->save();

        // 画像保存
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
        } else {
            $path = null;
        }

        // Profileテーブルに保存
        $user->profile()->create([
            'img_url' => $path,
            'postcode' => $validated['postcode'],
            'address' => $validated['address'],
            'building' => $validated['building'],
        ]);

        return redirect()->route('mylist');
    }



    public function editProfile()
    {
        $user = auth()->user();

        return view('profile', [
            'user' => $user,
            'isEdit' => true,
        ]);
    }


    public function updateProfile(ProfileRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->save();

        $profile = $user->profile;

        if (!$profile) {
            $profile = $user->profile()->create([
                'img_url' => null,
                'postcode' => '',
                'address' => '',
                'building' => '',
            ]);
        }

        if ($request->hasFile('profile_image')) {
            if ($profile->img_url && Storage::disk('public')->exists($profile->img_url)) {
                Storage::disk('public')->delete($profile->img_url);
            }
            $profile->img_url = $request->file('profile_image')->store('profiles', 'public');
        }

        $profile->postcode = $validated['postcode'];
        $profile->address = $validated['address'];
        $profile->building = $validated['building'];
        $profile->save();

        return redirect()->route('mypage');
    }


    public function editAddress(Request $request)
    {
        $user = Auth::user();
        $item_id = $request->query('item_id');

        return view('address', compact('user','item_id'));
    }

    public function updateAddress(AddressRequest $request)
    {

        $user = Auth::user();

        $validated = $request->validated();

        $user->profile->update($validated);

        $item_id = $request->input('item_id');

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
