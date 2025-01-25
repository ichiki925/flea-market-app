<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_mylist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/mylist');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_mylist()
    {
        $response = $this->get('/mylist');

        $response->assertRedirect('/login');
    }

}
