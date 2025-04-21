<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;

class ItemPurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_success_creates_sold_item()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create(); // 出品者を明示的に作成

        $item = Item::factory()->create([
            'status' => 'available',
            'user_id' => $seller->id, // ← 明示的に出品者IDを設定
        ]);

        $buyer->profile()->create([
            'postcode' => '106-0032',
            'address' => '東京都港区六本木1-1-1',
            'building' => '六本木ヒルズタワー',
        ]);

        $this->actingAs($buyer);

        $response = $this->get(route('payment.success', ['item_id' => $item->id]));

        $response->assertStatus(302);

        $this->assertDatabaseHas('sold_items', [
            'item_id' => $item->id,
            'user_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'payment_method' => 'card',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'trading',
        ]);
    }

    public function test_user_can_purchase_item_with_convenience_store()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'available', 'user_id' => User::factory()->create()->id]);


        $user->profile()->create([
            'postcode' => '106-0032',
            'address' => '東京都港区六本木1-1-1',
            'building' => '六本木ヒルズタワー',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'コンビニ払い',
        ]);

        $response->assertStatus(302);

    }


    public function test_purchased_item_shows_as_sold()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'sold']);

        $this->actingAs($user);

        $response = $this->get(route('index'));

        $response->assertStatus(200);

        $response->assertSee('Sold');
    }

    public function test_purchased_item_is_added_to_purchase_history()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'available', 'user_id' => User::factory()->create()->id]);


        SoldItem::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'buyer_id' => $user->id,
            'sending_postcode' => '106-0032',
            'sending_address' => '東京都港区六本木1-1-1',
            'payment_method' => 'card',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage', ['tab' => 'purchase']));

        $response->assertStatus(200);

        $response->assertSee($item->name);
    }
}
