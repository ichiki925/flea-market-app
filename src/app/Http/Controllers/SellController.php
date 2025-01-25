<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class SellController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        $item = new Item();

        return view('sell', compact('categories', 'conditions', 'item'));
    }


    public function store(ExhibitionRequest $request)
    {


        $validated = $request->validated();

        $item = new Item();
        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->price = $validated['price'];
        $item->user_id = Auth::id();
        $item->status = 'available';
        $item->condition_id = $validated['condition'];

        if ($request->hasFile('item_image')) {
            $path = $request->file('item_image')->store('items', 'public');
            $item->item_image = $path;

        }

        $item->save();

        if (isset($validated['item_categories'])) {
            $item->categories()->sync($validated['item_categories']);
        }

        return redirect()->route('mypage', ['tab' => 'sell'])->with('success', '商品を出品しました！');
    }



}
