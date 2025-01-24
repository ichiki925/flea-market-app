<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class PaymentMethodSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_method_selection_updates_summary()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $item = Item::factory()->create(['price' => 5000]);

        // ユーザーを認証
        $this->actingAs($user);

        // 支払い方法選択画面にアクセス
        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 支払い方法を「カード支払い」に変更
        $response = $this->get(route('purchase.show', [
            'item_id' => $item->id,
            'payment_method' => 'card',
        ]));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 小計画面に選択した支払い方法が反映されていることを確認
        $response->assertSee('カード支払い');
        $response->assertSee('¥5,000');
    }

    public function test_guest_cannot_access_payment_method_selection()
    {
        // テストデータの作成
        $item = Item::factory()->create();

        // 支払い方法選択画面にアクセス（未認証）
        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));

        // ステータスコードが302（リダイレクト）であることを確認
        $response->assertStatus(302);

        // ログインページにリダイレクトされることを確認
        $response->assertRedirect(route('login'));
    }

}
