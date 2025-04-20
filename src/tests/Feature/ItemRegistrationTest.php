<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;

class ItemRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_registration_saves_data_correctly()
    {
        Condition::query()->delete(); 

        $user = User::factory()->create();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $condition = Condition::firstOrCreate(['condition' => '良好']);

        $this->actingAs($user);

        Storage::fake('public');
        $image = UploadedFile::fake()->create('item_image.png', 500, 'image/png');

        $response = $this->post(route('sell.store'), [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品の説明です。',
            'price' => 1000,
            'condition' => $condition->id,
            'item_categories' => [$category1->id, $category2->id],
            'img_url' => $image,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品の説明です。',
            'price' => 1000,
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'status' => 'available',
        ]);

        $this->assertDatabaseHas('category_items', [
            'category_id' => $category1->id,
        ]);

        $this->assertDatabaseHas('category_items', [
            'category_id' => $category2->id,
        ]);

        Storage::disk('public')->assertExists('items/' . $image->hashName());
    }

    public function test_item_registration_validation_errors()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('sell.store'), []);

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'name',
            'description',
            'price',
            'condition',
            'item_categories',
        ]);
    }
}
