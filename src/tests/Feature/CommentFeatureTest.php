<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_send_comment()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーを認証
        $this->actingAs($user);

        // コメント送信リクエストを送信
        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'content' => 'This is a test comment.',
        ]);

        // ステータスコードが200またはリダイレクトを確認
        $response->assertStatus(302);

        // コメントがデータベースに保存されていることを確認
        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => 'This is a test comment.',
        ]);
    }

    public function test_guest_user_cannot_send_comment()
    {
        // テストデータの作成
        $item = Item::factory()->create();

        // コメント送信リクエストを送信
        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'content' => 'This is a test comment.',
        ]);

        // 認証エラーを確認
        $response->assertStatus(401);
    }

    public function test_comment_cannot_be_empty()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーを認証
        $this->actingAs($user);

        // 空のコメントを送信
        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'content' => '',
        ]);

        // バリデーションエラーを確認
        $response->assertSessionHasErrors(['content']);
    }

    public function test_comment_cannot_exceed_255_characters()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ユーザーを認証
        $this->actingAs($user);

        // 256文字のコメントを送信
        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'content' => str_repeat('a', 256),
        ]);

        // バリデーションエラーを確認
        $response->assertSessionHasErrors(['content']);
    }

}
