<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;
use Database\Seeders\ConditionSeeder;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_items_are_displayed()
    {
        $this->seed(ConditionSeeder::class);
        Item::factory()->count(5)->create();

        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_sold_items_are_displayed_as_sold()
    {
        $this->seed(ConditionSeeder::class);
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();
        SoldItem::factory()->create([
            'user_id' => $user->id,
            'buyer_id' => User::factory()->create()->id,
            'item_id' => $item->id,
        ]);
        $item->update(['status' => 'sold']);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);

        $response = $this->get('/mylist');
        $response->assertSee('Sold');
    }

    public function test_user_items_are_not_displayed()
    {
        $this->seed(ConditionSeeder::class);
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/mylist');
        $response->assertDontSee($item->name);
    }
}
