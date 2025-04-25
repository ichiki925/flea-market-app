<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\SoldItem;
use App\Models\Review;

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

    public function test_status_changes_to_sold_after_both_reviews()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $seller->id, 'status' => 'trading']);
        SoldItem::factory()->create(['item_id' => $item->id, 'buyer_id' => $buyer->id]);

        // 購入者が先にレビュー
        Review::factory()->create([
            'item_id' => $item->id,
            'reviewer_id' => $buyer->id,
            'reviewee_id' => $seller->id,
        ]);

        // 出品者がレビュー（評価送信ルートを呼び出す）
        $this->actingAs($seller);
        $response = $this->post(route('rating.submit', $item->id), [
            'rating' => 5,
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);
    }

}
