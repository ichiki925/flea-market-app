<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\SoldItem;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_address_change_reflects_in_purchase_screen()
    {
        $user = User::factory()->create();

        \App\Models\Profile::factory()->create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '旧住所',
            'building' => '旧建物名',
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('mypage.updateAddress'), [
            'postcode' => '987-6543',
            'address' => '新住所',
            'building' => '新建物名',
            'item_id' => $item->id,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'postcode' => '987-6543',
            'address' => '新住所',
            'building' => '新建物名',
        ]);

        $response->assertRedirect(route('purchase.show', ['item_id' => $item->id]));
    }

    public function test_purchased_item_is_registered_with_delivery_address()
    {
        $user = User::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
            'postcode' => '123-4567',
            'address' => '登録住所',
            'building' => '登録建物名',
        ]);

        $item = Item::factory()->create(['status' => 'available']);

        $this->actingAs($user);


        $response = $this->get(route('payment.success', ['item_id' => $item->id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('mypage', ['tab' => 'purchase']));


        $this->assertDatabaseHas('sold_items', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'sending_postcode' => '123-4567',
            'sending_address' => '登録住所',
            'sending_building' => '登録建物名',
            'payment_method' => 'card',
        ]);
    }


}
