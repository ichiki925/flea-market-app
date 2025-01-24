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
        // テスト用データの作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profiles/test_image.png',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        // ユーザーを認証
        $this->actingAs($user);

        // プロフィール編集ページにアクセス
        $response = $this->get(route('mypage.profile'));

        // ステータスコードが200であることを確認
        $response->assertStatus(200);

        // 初期値が正しく表示されていることを確認
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
        $response->assertSee('テストビル');
        $response->assertSee(asset('storage/profiles/test_image.png'));
    }

    public function test_profile_information_can_be_updated()
    {
        // テスト用データの作成
        $user = User::factory()->create();

        // ユーザーを認証
        $this->actingAs($user);

        // ストレージをモック
        Storage::fake('public');

        // プロフィール画像のアップロードファイルを作成
        $file = UploadedFile::fake()->image('new_profile_image.png');

        // 更新情報を送信
        $response = $this->put(route('mypage.update'), [
            'name' => '更新後ユーザー名',
            'profile_image' => $file,
            'postal_code' => '987-6543',
            'address' => '大阪市北区',
            'building' => '更新ビル',
        ]);

        // ステータスコードが302であることを確認（リダイレクト）
        $response->assertStatus(302);

        // データベースの内容が更新されていることを確認
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '更新後ユーザー名',
            'postal_code' => '987-6543',
            'address' => '大阪市北区',
            'building' => '更新ビル',
        ]);

        // 新しいプロフィール画像が保存されていることを確認
        Storage::disk('public')->assertExists('profiles/' . $file->hashName());
    }

}
