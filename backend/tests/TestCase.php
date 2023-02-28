<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Routing\Middleware\ThrottleRequests;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected const USER_DEFAULT_ID = '27ac0aa4-1ffc-475b-ae83-e55a0d17e9a4';
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::find(self::USER_DEFAULT_ID);
        $this->withoutMiddleware(ThrottleRequests::class);
    }
}
