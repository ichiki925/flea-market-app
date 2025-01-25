<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // 必要なテストでのみミドルウェアを無効化する
        if (property_exists($this, 'disableCsrfMiddleware') && $this->disableCsrfMiddleware) {
            $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        }
    }
}
