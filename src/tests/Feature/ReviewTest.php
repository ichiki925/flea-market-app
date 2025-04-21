<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_can_submit_review()
    {
        $seller = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create(['user_id' => $seller->id]);

        $item = Item::factory()->create(['user_id' => $seller->id, 'status' => 'trading']);

        $buyer = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create(['user_id' => $buyer->id]);

        $response = $this->actingAs($buyer)->post(route('rating.submit', ['item_id' => $item->id]), [
            'rating' => 4,
        ]);

        $response->assertRedirect(route('mylist', ['tab' => 'index']));
        $this->assertDatabaseHas('reviews', [
            'reviewer_id' => $buyer->id,
            'reviewee_id' => $seller->id,
            'item_id' => $item->id,
            'rating' => 4,
        ]);
    }
}
