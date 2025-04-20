<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ChatMessage;
use App\Models\Profile;

class MessageEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_edit_own_message()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Profile::factory()->create(['user_id' => $user->id]);

        $item = Item::factory()->create(['user_id' => $user->id]);

        $message = ChatMessage::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'message' => '元のメッセージ',
        ]);

        $this->actingAs($user)
            ->put(route('chat.update', ['message' => $message->id]), [
                'message' => '編集後のメッセージ',
            ])
            ->assertRedirect(route('chat.show', ['item' => $item->id]));

        $this->assertDatabaseHas('chat_messages', [
            'id' => $message->id,
            'message' => '編集後のメッセージ',
        ]);
    }

    public function test_guest_cannot_edit_message()
    {
        $message = ChatMessage::factory()->create();

        $this->put(route('chat.update', ['message' => $message->id]), [
            'message' => '編集しようとした内容',
        ])->assertRedirect('/login');
    }
}
