<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ItemPurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'available']);

        $this->actingAs($user);

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'address' => '東京都港区六本木1-1-1',
            'building' => '六本木ヒルズタワー',
            'postal_code' => '106-0032',
            'payment_method' => 'card',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'buyer_id' => $user->id,
            'payment_method' => 'card',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);
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
        $item = Item::factory()->create(['status' => 'available']);

        Purchase::factory()->create([
            'item_id' => $item->id,
            'buyer_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage', ['tab' => 'purchase']));

        $response->assertStatus(200);

        $response->assertSee($item->name);
    }
}
