<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\SoldItem;
use App\Mail\TransactionCompleted;

class MailNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_receives_email_after_review_submission()
    {
        Mail::fake();

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
            'rating' => 5,
        ]);

        $response->assertRedirect(route('mylist', ['tab' => 'index']));

        Mail::assertSent(TransactionCompleted::class, function ($mail) use ($seller) {
            return $mail->hasTo($seller->email);
        });
    }
}
