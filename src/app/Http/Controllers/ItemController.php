<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $items = Item::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->get();

        return view('index', compact('items'));
    }

    public function mylist(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $tab = $request->input('tab', 'mylist');

        if ($tab === 'mylist') {
            $items = Item::whereHas('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })->get();
        } else {
            $items = Item::when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })->get();
        }

        return view('mylist', compact('items', 'tab'));
    }

    public function show($id)
    {
        $item = Item::with(['categories', 'condition', 'comments.user'])->findOrFail($id);

        $viewName = auth()->check() ? 'item_detail' : 'item_detail_guest';

        return view($viewName, compact('item'));

    }

    public function storeComment(CommentRequest $request)
    {
        $validated = $request->validated();

        Comment::create([
            'content' => $validated['content'],
            'item_id' => $request->input('item_id'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('item.detail', ['id' => $request->input('item_id')])
                        ->with('success', 'コメントを送信しました！');
    }


}