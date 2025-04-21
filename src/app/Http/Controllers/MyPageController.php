<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Review;
use App\Models\ChatMessage;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->input('tab', 'sell');


        if ($tab === 'sell') {
            $items = $user->items()->get();
        } elseif ($tab === 'purchase') {
            $items = Item::whereHas('soldItems', function ($query) use ($user) {
                $query->where('buyer_id', $user->id);
            })->get();
        } else {
            $items = Item::whereHas('soldItems', function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('user_id', $user->id);
            })
            ->with('soldItems')
            ->get();
        }


        $unreadCounts = $this->getUnreadCounts($user);

        return view('mypage', [
            'tab' => $tab,
            'items' => $items,
            'unreadCounts' => $unreadCounts,
        ]);
    }


    public function purchases()
    {
        $user = auth()->user();


        $items = Item::whereHas('soldItems', function ($query) use ($user) {
            $query->where('buyer_id', $user->id);
        })->get();


        $unreadCounts = $this->getUnreadCounts($user);

        $tradingCount = \App\Models\Item::where('status', 'trading')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('soldItems', fn($q) => $q->where('user_id', $user->id));
            })
            ->count();

        return view('mypage', [
            'items' => $items,
            'tab' => 'purchase',
            'tradingCount' => $tradingCount,
            'unreadCounts' => $unreadCounts,
        ]);
    }


    public function trading()
    {
        $user = auth()->user();

        $items = Item::where('status', 'trading')
            ->whereHas('soldItems', function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('user_id', $user->id);
            })
            ->with(['soldItems', 'chatMessages'])
            ->get();

        $unreadCounts = [];

        foreach ($items as $item) {
            $unreadCounts[$item->id] = \App\Models\ChatMessage::where('item_id', $item->id)
                ->whereNull('read_at')
                ->where('user_id', '!=', $user->id)
                ->count();
        }

        return view('mypage', [
            'tab' => 'trading',
            'items' => $items,
            'unreadCounts' => $unreadCounts,
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


        $user->name = $validated['name'];
        $user->save();


        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
        } else {
            $path = null;
        }


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

    private function getUnreadCounts($user)
    {
        $items = Item::where('status', 'trading')
            ->whereHas('soldItems', function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('user_id', $user->id);
            })
            ->with('soldItems')
            ->get();

        $unreadCounts = [];

        foreach ($items as $item) {
            $unreadCounts[$item->id] = ChatMessage::where('item_id', $item->id)
                ->whereNull('read_at')
                ->where('user_id', '!=', $user->id)
                ->count();
        }

        return $unreadCounts;
    }

    public function showProfile()
    {
        $user = auth()->user();


        $reviews = Review::where('reviewee_id', $user->id)->get();
        $averageRating = $reviews->avg('rating');

        return view('mypage', [
            'user' => $user,
            'reviews' => $reviews,
            'averageRating' => round($averageRating, 1)
        ]);
    }

}
