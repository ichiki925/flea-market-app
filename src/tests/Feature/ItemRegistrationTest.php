<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;

class ItemRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_registration_saves_data_correctly()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $condition = Condition::factory()->create();

        // ユーザーを認証
        $this->actingAs($user);

        // 出品情報を送信
        $response = $this->post(route('sell.store'), [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品の説明です。',
            'price' => 1000,
            'condition' => $condition->id,
            'item_categories' => [$category1->id, $category2->id],
            'item_image' => null, // 画像は省略
        ]);

        // ステータスコードが302であることを確認（リダイレクト）
        $response->assertStatus(302);

        // 商品がデータベースに保存されていることを確認
        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品の説明です。',
            'price' => 1000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'status' => 'available',
        ]);

        // カテゴリーが中間テーブルに保存されていることを確認
        $this->assertDatabaseHas('item_categories', [
            'category_id' => $category1->id,
        ]);
        $this->assertDatabaseHas('item_categories', [
            'category_id' => $category2->id,
        ]);
    }

    public function test_item_registration_validation_errors()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $this->actingAs($user);

        // 必須項目を空にして送信
        $response = $this->post(route('sell.store'), []);

        // ステータスコードが302であることを確認（リダイレクト）
        $response->assertStatus(302);

        // バリデーションエラーが返されていることを確認
        $response->assertSessionHasErrors([
            'name',
            'description',
            'price',
            'condition',
            'item_categories',
        ]);
    }
}
