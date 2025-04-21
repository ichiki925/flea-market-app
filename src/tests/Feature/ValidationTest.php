<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\SoldItem;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_rating_is_required()
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

        $response = $this->actingAs($buyer)->post(route('rating.submit', $item->id), [

        ]);

        $response->assertSessionHasErrors(['rating']);
    }

    public function test_rating_must_be_between_1_and_5()
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

        $response = $this->actingAs($buyer)->post(route('rating.submit', $item->id), [
            'rating' => 0,
        ]);

        $response->assertSessionHasErrors(['rating']);
    }
}
