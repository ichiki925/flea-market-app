<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ChatMessage;
use App\Models\SoldItem;
use App\Http\Requests\ChatMessageRequest;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function show(Item $item)
    {
        $user = Auth::user();

        ChatMessage::where('item_id', $item->id)
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $item->chatMessages()->with('user.profile')->get();


        $isBuyer = $item->soldItems()->where('buyer_id', $user->id)->exists();
        $isSeller = $item->user_id === $user->id;


        if ($isBuyer) {
            $partner = $item->user;
        } elseif ($isSeller) {
            $soldItem = $item->soldItems()->with('buyer')->first();
            // 購入者がまだいない可能性もあるので null 安全に取得
            $partner = $soldItem ? $soldItem->buyer : null;
        } else {
            abort(403, 'この取引に関与していません');
        }


        // サイドバー用
        $myItems = Item::where('status', 'trading')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('soldItems', function ($q) use ($user) {
                        $q->where('buyer_id', $user->id);
                    });
            })
            ->with([
                'chatMessages' => fn($q) => $q->latest(),
                'user.profile',
            ])
            ->withCount(['chatMessages as unread_count' => function ($query) use ($user) {
                $query->where('user_id', '!=', $user->id)
                    ->whereNull('read_at');
            }])
            ->get()
            ->sortByDesc(fn($item) => optional($item->chatMessages->last())->created_at);




        $isEditMode = false;

        if (request()->routeIs('chat.edit') && $message = ChatMessage::find(request()->route('message'))) {
            $isEditMode = true;
        }

        return view('chat', compact('item', 'messages', 'isBuyer', 'isSeller', 'myItems', 'partner', 'isEditMode'));
    }


    public function send(ChatMessageRequest $request, Item $item)
    {
        $chatMessage = new ChatMessage();
        $chatMessage->user_id = auth()->id();
        $chatMessage->item_id = $item->id;
        $chatMessage->message = $request->input('message');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_images', 'public');
            $chatMessage->image_path = $path;
        }


        $chatMessage->save();

        return redirect()->route('chat.show', $item->id)->with('status', 'メッセージを送信しました');
    }

    public function destroy(ChatMessage $message)
    {
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $itemId = $message->item_id;

        $message->delete();

        return redirect()->route('chat.show', $itemId)->with('status', 'メッセージを削除しました');
    }

    public function edit(ChatMessage $message)
    {
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $item = Item::with(['chatMessages.user.profile'])->find($message->item_id);
        $messages = $item->chatMessages;

        $user = auth()->user();

        $myItems = Item::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereHas('soldItems', fn($q) => $q->where('user_id', $user->id));
        })->where('status', 'trading')->get();

        $isBuyer = $item->soldItems()->where('user_id', $user->id)->exists();
        $isSeller = $item->user_id === $user->id;

        $partner = $isBuyer ? $item->user : optional($item->soldItems()->with('user')->first())->user;

        return view('chat', compact('item', 'messages', 'isBuyer', 'isSeller', 'myItems', 'partner', 'message'));
    }

    public function update(ChatMessageRequest $request, ChatMessage $message)
    {
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $message->message = $request->input('message');

        if ($request->hasFile('image')) {
            if ($message->image_path && Storage::disk('public')->exists($message->image_path)) {
                Storage::disk('public')->delete($message->image_path);
            }
            $message->image_path = $request->file('image')->store('chat_images', 'public');
        }

        $message->save();

        return redirect()->route('chat.show', $message->item_id)->with('status', 'メッセージを更新しました');
    }





}
