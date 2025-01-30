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

        $validated = $request->validated();

        $user = auth()->user();

        $user->fill($validated);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }


        $user->save();

        Auth::setUser($user);

        $redirectTo = $request->input('redirect_to', route('mylist'));

        return redirect($redirectTo);
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


        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $path;
        } else {
            $validated['profile_image'] = $user->profile_image;
        }


        $user->update($validated);

        Auth::setUser($user);

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

        $user->update($validated);

        $item_id = $request->input('item_id');

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
