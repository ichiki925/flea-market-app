<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_items_are_displayed()
    {
        Item::factory()->count(5)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

    }

    public function test_sold_items_are_displayed_as_sold()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();
        Purchase::factory()->create([
            'buyer_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $item->update(['status' => 'sold']); // ステータスを変更

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);

        // /mylist にアクセスして "Sold" が表示されるか確認
        $response = $this->get('/mylist');
        $response->assertSee('Sold');
    }

    public function test_user_items_are_not_displayed()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/mylist');

        $response->assertDontSee($item->name);
    }
}
