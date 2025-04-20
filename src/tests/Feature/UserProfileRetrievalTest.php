<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class UserProfileRetrievalTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_profile_page_displays_user_information()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $profile = \App\Models\Profile::factory()->create([
            'user_id' => $user->id,
            'img_url' => 'profiles/example.png',
        ]);

        $items = Item::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage', ['tab' => 'sell']));

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee(asset('storage/' . $profile->img_url));

        foreach ($items as $item) {
            $response->assertSee($item->name);
            $response->assertSee(asset('storage/' . $item->img_url));
        }
    }



    public function test_user_profile_page_displays_purchased_items()
    {
        $user = User::factory()->create();
        $purchasedItems = Item::factory()->count(2)->create();

        foreach ($purchasedItems as $item) {
            \App\Models\SoldItem::create([
                'item_id' => $item->id,
                'user_id' => $item->user_id, // 出品者
                'buyer_id' => $user->id,      // 購入者
                'sending_address' => 'テスト住所',
                'sending_building' => 'テスト建物名',
                'sending_postcode' => '123-4567',
                'payment_method' => 'card',
            ]);
        }

        $this->actingAs($user);

        $response = $this->get(route('mypage.purchases'));

        $response->assertStatus(200);

        foreach ($purchasedItems as $item) {
            $response->assertSee($item->name);
            $response->assertSee(asset('storage/' . $item->img_url));
        }
    }
}
