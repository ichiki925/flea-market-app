<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Like;
use App\Models\Condition;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_detail_displays_required_information()
    {
        Condition::query()->delete(); 

        $user = User::factory()->create();
        $category = Category::factory()->create(['category' => 'Electronics']);
        $condition = \App\Models\Condition::firstOrCreate(
            ['condition' => '良好'],
            ['id' => 1]
        );

        $item = Item::factory()->create([
            'name' => 'Test Item',
            'brand' => 'Test Brand',
            'description' => 'This is a test description.',
            'price' => 1000,
            'condition_id' => $condition->id,
            'user_id' => $user->id,
        ]);

        $item->categories()->attach($category->id);

        Comment::factory()->count(2)->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        Like::factory()->count(3)->create(['item_id' => $item->id]);


        $response = $this->get(route('item.detail', ['id' => $item->id]));

        $response->assertStatus(200);

        $response->assertSee('Test Item');
        $response->assertSee('Test Brand');
        $response->assertSee('This is a test description.');
        $response->assertSee('¥1,000');
        $response->assertSee('Electronics');
        $response->assertSee('コメント(2)');
        $response->assertSeeInOrder(['<span class="likes_count">', '3', '</span>'], false);
    }
}
