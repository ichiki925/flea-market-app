<?php

namespace Tests\Feature;


use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\ChatMessage;
use App\Models\SoldItem;

class TradeChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_trade_chat()
    {
        $seller = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create(['user_id' => $seller->id]);

        $buyer = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create(['user_id' => $buyer->id]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'trading',
        ]);

        SoldItem::factory()->create([
            'item_id' => $item->id,
            'user_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);

        ChatMessage::factory()->create([
            'item_id' => $item->id,
            'user_id' => $buyer->id,
            'message' => 'こんにちは！これはテストです。',
        ]);

        $response = $this->actingAs($seller)->get(route('chat.show', ['item' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee('こんにちは！これはテストです。');
        $response->assertSee($item->name);
    }



    public function test_guest_cannot_view_trade_chat()
    {
        $item = Item::factory()->create();

        $response = $this->get(route('chat.show', ['item' => $item->id]));

        $response->assertRedirect('/login');
    }

    public function test_unread_badge_shows_only_in_trading_tab()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'status' => 'trading',
        ]);


        SoldItem::factory()->create([
            'item_id' => $item->id,
            'buyer_id' => $user->id,
            'user_id' => $user->id,
        ]);


        ChatMessage::factory()->create([
            'item_id' => $item->id,
            'user_id' => User::factory()->create()->id,
            'read_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage?tab=trading');
        $response->assertSee('unread-badge');

        $response = $this->get('/mypage?tab=sell');
        $response->assertDontSee('unread-badge');
    }

    public function test_user_can_send_text_message_only()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'trading']);

        $this->actingAs($user)
            ->post(route('chat.send', $item->id), [
                'message' => 'テキストメッセージのみ送信',
            ])
            ->assertRedirect(route('chat.show', $item->id));

        $this->assertDatabaseHas('chat_messages', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'message' => 'テキストメッセージのみ送信',
        ]);
    }

    public function test_user_can_send_image_only()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'trading']);

        $response = $this->actingAs($user)
            ->post(route('chat.send', $item->id), [
                'image' => UploadedFile::fake()->create('test-image.png', 500, 'image/png'),
            ]);

        $response->assertRedirect(route('chat.show', $item->id));

        $this->assertDatabaseCount('chat_messages', 1);

        $chatMessage = ChatMessage::first();
        Storage::disk('public')->assertExists($chatMessage->image_path);
    }


}
