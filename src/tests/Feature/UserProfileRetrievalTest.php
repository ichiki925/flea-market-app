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
            'profile_image' => 'profiles/example.png',
            'name' => 'テストユーザー',
        ]);

        $items = Item::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage', ['tab' => 'sell']));

        $response->assertStatus(200);

        $response->assertSee('テストユーザー');
        $response->assertSee(asset('storage/profiles/example.png'));

        foreach ($items as $item) {
            $response->assertSee($item->name);
            $response->assertSee(asset('storage/' . $item->item_image));
        }
    }

    public function test_user_profile_page_displays_purchased_items()
    {
        $user = User::factory()->create();
        $purchasedItems = Item::factory()->count(2)->create();

        foreach ($purchasedItems as $item) {
            $user->purchases()->create([
                'item_id' => $item->id,
            ]);
        }

        $this->actingAs($user);

        $response = $this->get(route('mypage.purchases'));

        $response->assertStatus(200);

        foreach ($purchasedItems as $item) {
            $response->assertSee($item->name);
            $response->assertSee(asset('storage/' . $item->item_image));
        }
    }
}
