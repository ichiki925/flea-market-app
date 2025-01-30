<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserProfileEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_edit_page_displays_initial_values()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profiles/test_image.png',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage.profile'));

        $response->assertStatus(200);

        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
        $response->assertSee('テストビル');
        $response->assertSee(asset('storage/profiles/test_image.png'));
    }

    public function test_profile_information_can_be_updated()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Storage::fake('public');

        $file = UploadedFile::fake()->create('new_profile_image.png', 500, 'image/png');

        $response = $this->put(route('mypage.update'), [
            'name' => '更新後ユーザー名',
            'profile_image' => $file,
            'postal_code' => '987-6543',
            'address' => '大阪市北区',
            'building' => '更新ビル',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '更新後ユーザー名',
            'postal_code' => '987-6543',
            'address' => '大阪市北区',
            'building' => '更新ビル',
        ]);

        Storage::disk('public')->assertExists('profiles/' . $file->hashName());
    }

}
