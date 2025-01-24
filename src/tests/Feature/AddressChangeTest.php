<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_address_change_reflects_in_purchase_screen()
    {
        // テストデータの作成
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '旧住所',
            'building' => '旧建物名',
        ]);
        $item = Item::factory()->create();

        // ユーザーを認証
        $this->actingAs($user);

        // 配送先住所を更新
        $response = $this->post(route('mypage.updateAddress'), [
            'postal_code' => '987-6543',
            'address' => '新住所',
            'building' => '新建物名',
            'item_id' => $item->id,
        ]);

        // ステータスコードが302であることを確認（リダイレクト）
        $response->assertStatus(302);

        // 更新された住所がデータベースに保存されていることを確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'postal_code' => '987-6543',
            'address' => '新住所',
            'building' => '新建物名',
        ]);

        // 購入画面にリダイレクトされていることを確認
        $response->assertRedirect(route('purchase.show', ['item_id' => $item->id]));
    }

    public function test_purchased_item_is_registered_with_delivery_address()
    {
        // テストデータの作成
        $user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '登録住所',
            'building' => '登録建物名',
        ]);
        $item = Item::factory()->create();

        // ユーザーを認証
        $this->actingAs($user);

        // 商品を購入
        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'card',
        ]);

        // ステータスコードが302であることを確認
        $response->assertStatus(302);

        // 購入情報がデータベースに保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'buyer_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '登録住所',
            'building' => '登録建物名',
        ]);
    }

}
