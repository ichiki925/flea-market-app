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
        // テストユーザーとアイテムを作成
        $user = User::factory()->create();
        Item::factory()->create(['name' => 'TestProduct']);
        Item::factory()->create(['name' => 'AnotherProduct']);

        // ユーザーを認証
        $this->actingAs($user);

        // 検索クエリを実行
        $response = $this->get('/?search=Test');

        // ステータスコードを確認
        $response->assertStatus(200);

        // 部分一致するアイテムが表示されていることを確認
        $response->assertSee('TestProduct');

        // 一致しないアイテムが表示されていないことを確認
        $response->assertDontSee('AnotherProduct');
    }

    public function test_search_state_is_retained_in_mylist_tab()
    {
        // テストユーザーとアイテムを作成
        $user = User::factory()->create();
        Item::factory()->create(['name' => 'FavoriteProduct']);

        // ユーザーを認証
        $this->actingAs($user);

        // マイリストタブで検索クエリを実行
        $response = $this->get('/mylist?tab=mylist&search=Favorite');

        // ステータスコードを確認
        $response->assertStatus(200);

        // 検索結果が正しく表示されていることを確認
        $response->assertSee('FavoriteProduct');
    }

}
