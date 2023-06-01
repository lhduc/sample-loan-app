<?php

namespace Tests\Feature;

use App\Constants\UserType;
use App\Models\User;
use Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public User $user;
    public User $admin;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::where('type', UserType::USER)->first();
        $this->admin = User::where('type', UserType::ADMIN)->first();
    }

    /**
     * @return string
     */
    public function getUserToken(): string
    {
        return $this->user->createToken('api-token')->plainTextToken;
    }

    /**
     * @return string
     */
    public function getAdminToken(): string
    {
        return $this->admin->createToken('api-token')->plainTextToken;
    }
}