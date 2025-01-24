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
        // テスト用の商品データを作成
        Item::factory()->count(5)->create();

        // 商品一覧ページにアクセス
        $response = $this->get('/items');

        // ステータスコード200を確認
        $response->assertStatus(200);

        // 全商品の名前がレスポンスに含まれていることを確認
        $items = Item::all();
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_sold_items_are_displayed_as_sold()
    {
        // テスト用のユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 購入情報を作成
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品一覧ページにアクセス
        $response = $this->get('/items');

        // 購入済み商品に「Sold」が表示されていることを確認
        $response->assertSee('Sold');
    }

    public function test_user_items_are_not_displayed()
    {
        // テスト用のユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        // ログイン状態で商品一覧ページにアクセス
        $response = $this->actingAs($user)->get('/items');

        // 自分が出品した商品が表示されていないことを確認
        $response->assertDontSee($item->name);
    }
}
