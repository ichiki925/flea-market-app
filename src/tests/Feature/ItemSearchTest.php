<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_search_items_by_partial_name()
    {
        $user = User::factory()->create();
        Item::factory()->create(['name' => 'TestProduct']);
        Item::factory()->create(['name' => 'AnotherProduct']);

        $this->actingAs($user);

        $response = $this->get('/?search=Test');

        $response->assertStatus(200);

        $response->assertSee('TestProduct');

        $response->assertDontSee('AnotherProduct');
    }

    public function test_search_state_is_retained_in_mylist_tab()
    {
        $user = User::factory()->create();
        Item::factory()->create(['name' => 'FavoriteProduct']);

        $this->actingAs($user);

        $response = $this->get('/mylist?tab=mylist&search=Favorite');

        $response->assertStatus(200);

        $response->assertSee('FavoriteProduct');
    }

}
