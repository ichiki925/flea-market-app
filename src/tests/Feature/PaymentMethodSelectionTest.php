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
        $user = User::factory()->create();
        $item = Item::factory()->create(['price' => 5000]);

        $this->actingAs($user);

        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));

        $response->assertStatus(200);

        $response = $this->get(route('purchase.show', [
            'item_id' => $item->id,
            'payment_method' => 'card',
        ]));

        $response->assertStatus(200);

        $response->assertSee('カード支払い');
        $response->assertSee('¥5,000');
    }

    public function test_guest_cannot_access_payment_method_selection()
    {
        $item = Item::factory()->create();

        $response = $this->get(route('purchase.show', ['item_id' => $item->id]));

        $response->assertStatus(302);

        $response->assertRedirect(route('login'));
    }

}
