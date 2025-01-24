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

    public function test_example()
    {
        $response = $this->get('/mypage');
        $response->assertStatus(200);
    }

}
