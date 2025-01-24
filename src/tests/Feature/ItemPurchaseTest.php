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
        // テストデータの作成
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'available']);

        // ユーザーを認証
        $this->actingAs($user);

        // 購入処理を送信
        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'card',
        ]);

        // ステータスコードがリダイレクト(302)であることを確認
        $response->assertStatus(302);

        // 購入情報がデータベースに保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'buyer_id' => $user->id,
            'payment_method' => 'card',
        ]);

        // 商品のステータスが「sold」に更新されていることを確認
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);
    }

    public function test_purchased_item_shows_as_sold()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'sold']);

        // ユーザーを認証
        $this->actingAs($user);

        // 商品一覧ページにアクセス
        $response = $this->get(route('index'));

        // ステータスコードを確認
        $response->assertStatus(200);

        // 「sold」と表示されていることを確認
        $response->assertSee('Sold');
    }

    public function test_purchased_item_is_added_to_purchase_history()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => 'available']);

        // 購入情報を作成
        Purchase::factory()->create([
            'item_id' => $item->id,
            'buyer_id' => $user->id,
        ]);

        // ユーザーを認証
        $this->actingAs($user);

        // 購入履歴ページにアクセス
        $response = $this->get(route('mypage', ['tab' => 'purchase']));

        // ステータスコードを確認
        $response->assertStatus(200);

        // 購入した商品が表示されていることを確認
        $response->assertSee($item->name);
    }
}
