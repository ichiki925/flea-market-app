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


        // クエリビルダーを保持
        $itemsQuery = Item::query();

        if ($tab === 'mylist') {
            // ユーザーがいいねしたアイテム または 売り切れアイテムを取得
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
            // 通常の検索
            $itemsQuery->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            });
        }

        // クエリのSQLをログに出力してデバッグ
        \Log::info("Generated SQL: " . $itemsQuery->toSql(), $itemsQuery->getBindings());

        // データを取得
        $items = $itemsQuery->get();

        return view('mylist', compact('items', 'tab'));
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
            'content' => $validated['content'],
            'item_id' => $request->input('item_id'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('item.detail', ['id' => $request->input('item_id')])
                        ->with('success', 'コメントを送信しました！');
    }


}