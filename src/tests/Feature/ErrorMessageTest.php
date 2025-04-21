<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\SoldItem;

class ErrorMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_rating_error_message_is_displayed_on_failure()
    {
        $seller = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create(['user_id' => $seller->id]);

        $buyer = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create(['user_id' => $buyer->id]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'trading',
        ]);

        SoldItem::factory()->create([
            'user_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
        ]);


        $response = $this->actingAs($buyer)->from(route('chat.show', $item->id))
            ->post(route('rating.submit', $item->id), []);


        $response->assertRedirect(route('chat.show', $item->id));

        $response = $this->get(route('chat.show', $item->id));
        $response->assertSee('rating', false);
    }
}
