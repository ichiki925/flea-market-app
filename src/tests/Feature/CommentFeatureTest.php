<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_send_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'comment' => 'This is a test comment.',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'This is a test comment.',
        ]);
    }

    public function test_guest_user_cannot_send_comment()
    {
        $item = Item::factory()->create();

        $response = $this->postJson(route('comments.store', ['item_id' => $item->id]), [
            'comment' => 'This is a test comment.',
        ]);

        $response->assertStatus(401);
    }

    public function test_comment_cannot_be_empty()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']);
    }

    public function test_comment_cannot_exceed_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'comment' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors(['comment']);
    }

}
