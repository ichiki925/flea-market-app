<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;
use App\Models\ChatMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatMessageOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_messages_are_ordered_by_creation_time()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'trading',
        ]);

        SoldItem::factory()->create([
            'item_id' => $item->id,
            'user_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);

        $this->actingAs($buyer);

        ChatMessage::factory()->create([
            'item_id' => $item->id,
            'user_id' => $buyer->id,
            'message' => '先に送ったメッセージ',
            'created_at' => now()->subMinutes(5),
        ]);

        ChatMessage::factory()->create([
            'item_id' => $item->id,
            'user_id' => $seller->id,
            'message' => '後から送ったメッセージ',
            'created_at' => now(),
        ]);

        $response = $this->get(route('chat.show', $item->id));

        $response->assertStatus(200);

        // 順番を確認（先に送ったメッセージが先に表示されているか）
        $response->assertSeeInOrder([
            '先に送ったメッセージ',
            '後から送ったメッセージ',
        ]);
    }
}