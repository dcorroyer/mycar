<?php

namespace Tests\Unit\Actions\Authentication;

use App\Actions\Authentication\Login;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @group login
 * @group login-unit
 */
class LoginTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'email' => 'john@mycar.local',
            'password' => Hash::make('password'),
        ]);
    }

    /** @test */
    public function it_can_login_unit()
    {
        Login::run([
            'email' => 'john@mycar.local',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }
}
