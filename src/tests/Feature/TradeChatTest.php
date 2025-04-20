<?php

namespace Tests\Feature;

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
        $user = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => 'テスト市',
            'building' => 'テストビル',
        ]);

        // 🟡 パートナー（チャットの相手）を作成
        $partner = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create([
            'user_id' => $partner->id,
            'postcode' => '987-6543',
            'address' => '別の市',
            'building' => '別のビル',
            'img_url' => 'dummy.jpg', // テスト用画像（public/storage/dummy.jpg があればOK）
        ]);

        // ユーザーが出品した商品
        $item = Item::factory()->create(['user_id' => $user->id]);

        SoldItem::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,     // 出品者
            'buyer_id' => $partner->id, // 購入者
            'payment_method' => 'card',
            'sending_postcode' => $partner->profile->postcode,
            'sending_address' => $partner->profile->address,
            'sending_building' => $partner->profile->building,
        ]);

        // 相手が送信したメッセージ
        ChatMessage::factory()->create([
            'item_id' => $item->id,
            'user_id' => $partner->id,
            'message' => 'こんにちは！これはテストです。',
        ]);

        $response = $this->actingAs($user)->get(route('chat.show', ['item' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee('こんにちは！これはテストです。');
        $response->assertSee($item->name);
    }

    public function test_guest_cannot_view_trade_chat()
    {
        // 👇 これを追加！
        $item = Item::factory()->create();

        $response = $this->get(route('chat.show', ['item' => $item->id]));

        $response->assertRedirect('/login');
    }
}
