<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Like;
use App\Models\Item;

class LikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    // CSRFミドルウェアを無効化する
    protected bool $disableCsrfMiddleware = true;

    public function testToggleLikeForAuthenticatedUser()
    {
        // 認証ユーザーを作成
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'web');

        // 認証状態を確認
        $this->assertTrue(auth()->check(), 'User is not authenticated.');

        // テスト用の商品を作成
        $item = \App\Models\Item::factory()->create();

        // 初回いいね
        $response = $this->postJson("/likes/toggle/{$item->id}");
        $response->assertStatus(200)
                ->assertJson(['status' => 'liked']);

        // いいね解除
        $response = $this->postJson("/likes/toggle/{$item->id}");
        $response->assertStatus(200)
                ->assertJson(['status' => 'unliked']);
    }

    public function testGuestCannotLike()
    {
        $item = \App\Models\Item::factory()->create();

        // 認証なしでいいねを試みる
        $response = $this->postJson("/likes/toggle/{$item->id}");
        $response->assertStatus(401)
                ->assertJson(['message' => 'Unauthenticated.']);
    }


}
