<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class UserProfileRetrieval extends TestCase
{
    use RefreshDatabase;

    public function test_user_profile_page_displays_user_information()
    {
        // テスト用データの作成
        $user = User::factory()->create([
            'profile_image' => 'profiles/example.png',
            'name' => 'テストユーザー',
        ]);

        $items = Item::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        // ユーザーを認証
        $this->actingAs($user);

        // プロフィールページにアクセス
        $response = $this->get(route('mypage', ['tab' => 'sell']));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // プロフィール情報が表示されていることを確認
        $response->assertSee('テストユーザー');
        $response->assertSee(asset('storage/profiles/example.png'));

        // 出品した商品の情報が表示されていることを確認
        foreach ($items as $item) {
            $response->assertSee($item->name);
            $response->assertSee(asset('storage/' . $item->item_image));
        }
    }

    public function test_user_profile_page_displays_purchased_items()
    {
        // テスト用データの作成
        $user = User::factory()->create();
        $purchasedItems = Item::factory()->count(2)->create();

        // 購入データを関連付け
        foreach ($purchasedItems as $item) {
            $user->purchases()->create([
                'item_id' => $item->id,
            ]);
        }

        // ユーザーを認証
        $this->actingAs($user);

        // 購入商品タブにアクセス
        $response = $this->get(route('mypage.purchases'));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 購入商品の情報が表示されていることを確認
        foreach ($purchasedItems as $item) {
            $response->assertSee($item->name);
            $response->assertSee(asset('storage/' . $item->item_image));
        }
    }
}
