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

        $layout = auth()->check() ? 'layouts.app' : 'layouts.guest';

        return view('index', compact('items', 'layout'));
    }

    public function mylist(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $tab = $request->input('tab', $request->has('search') ? 'index' : 'mylist');


        $itemsQuery = Item::query();

        if ($tab === 'mylist') {
            $itemsQuery->whereHas('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orWhere(function ($query) use ($search) {
                $query->where('status', 'sold')
                        ->when($search, function ($query, $search) {
                            return $query->where('name', 'like', '%' . $search . '%');
                        });
            });
        } else {
            $itemsQuery->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            });
        }

        \Log::info("Generated SQL: " . $itemsQuery->toSql(), $itemsQuery->getBindings());

        $items = $itemsQuery->get();

        return view('mylist', compact('items', 'tab'));
    }

    public function redirectTop(Request $request)
    {
        if (auth()->check()) {
            $request->merge(['tab' => 'index']);
            return $this->mylist($request);
        } else {
            return $this->index($request);
        }
    }


    public function show(Request $request, $id)
    {
        $item = Item::with(['categories', 'condition', 'comments.user'])->findOrFail($id);

        $viewName = auth()->check() ? 'item_detail' : 'item_detail_guest';

        return view($viewName, compact('item'));

    }

    public function storeComment(CommentRequest $request)
    {
        $validated = $request->validated();

        Comment::create([
            'comment' => $validated['comment'],
            'item_id' => $request->input('item_id'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('item.detail', ['id' => $request->input('item_id')])
                        ->with('success', 'コメントを送信しました！');
    }


}