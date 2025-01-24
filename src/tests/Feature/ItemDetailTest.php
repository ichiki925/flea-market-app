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

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_detail_displays_required_information()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $category = Category::factory()->create(['name' => 'Electronics']);
        $item = Item::factory()->create([
            'name' => 'Test Item',
            'brand' => 'Test Brand',
            'description' => 'This is a test description.',
            'price' => 1000,
            'condition_id' => 1,
            'user_id' => $user->id,
        ]);

        Comment::factory()->count(2)->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        Like::factory()->count(3)->create(['item_id' => $item->id]);

        // 商品詳細ページにアクセス
        $response = $this->get(route('item.detail', ['id' => $item->id]));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 必要な情報が表示されていることを確認
        $response->assertSee('Test Item');
        $response->assertSee('Test Brand');
        $response->assertSee('This is a test description.');
        $response->assertSee('1000');
        $response->assertSee('Electronics');
        $response->assertSee('2 コメント');
        $response->assertSee('3 いいね');
    }
}
