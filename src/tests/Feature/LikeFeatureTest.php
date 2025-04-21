<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Like;
use App\Models\Item;

class LikeFeatureTest extends TestCase
{
    use RefreshDatabase;


    protected bool $disableCsrfMiddleware = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function testToggleLikeForAuthenticatedUser()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $item = Item::factory()->create();

        $response = $this->postJson("/likes/toggle/{$item->id}", [], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200)->assertJson(['status' => 'liked']);
    }

    public function testGuestCannotLike()
    {
        $item = \App\Models\Item::factory()->create();


        $response = $this->postJson("/likes/toggle/{$item->id}");
        $response->assertStatus(401)
                ->assertJson(['message' => 'Unauthenticated.']);
    }


}
