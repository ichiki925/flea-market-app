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

        $itemsQuery = Item::query();

        if (auth()->check()) {
            $itemsQuery->where('user_id', '!=', auth()->id());
        }

        if ($search) {
            $itemsQuery->where('name', 'like', '%' . $search . '%');
        }

        $items = $itemsQuery->get();

        $layout = auth()->check() ? 'layouts.app' : 'layouts.guest';

        return view('index', compact('items', 'layout'));
    }

    public function mylist(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $tab = $request->input('tab') ?? 'mylist';



        $itemsQuery = Item::query();

        if ($tab === 'mylist') {
            $itemsQuery->whereHas('likes', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('user_id', '!=', $user->id);

            if ($search) {
                $itemsQuery->where('name', 'like', '%' . $search . '%');
            }
        } elseif ($tab === 'index') {
            $itemsQuery->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            });

            if ($user) {
                $itemsQuery->where('user_id', '!=', $user->id);

                $itemsQuery->whereDoesntHave('soldItems', function ($query) use ($user) {
                    $query->where('buyer_id', $user->id);
                });
            }
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