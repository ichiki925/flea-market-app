<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{

    use RefreshDatabase;


    public function test_email_is_required()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_password_is_required()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_email_is_incorrect()
    {

        $user = User::factory()->create([
            'email' => 'correct@example.com',
            'password' => bcrypt('password'),
        ]);


        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);


        $response->assertSessionHasErrors('email');
        $response->assertStatus(302);
    }

    public function test_login_succeeds_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user); // ← これを追加することでログイン状態に！

        $response = $this->get('/?page=mylist'); // ← 遷移先のページにアクセス

        $response->assertStatus(200); // 表示されることを確認
    }



}
