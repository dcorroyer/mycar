<?php

namespace Tests\Unit\Actions\User;

use App\Actions\User\ResetPassword;
use App\Events\User\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

/**
 * @group user
 * @group user-unit
 * @group user-update
 * @group user-update-unit
 */
class ResetPasswordTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(PasswordReset::class);
    }

    /** @test */
    public function it_can_reset_password_unit()
    {
        $user = User::factory()->create();

        $data = [
            'token' => Password::broker()->createToken($user),
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = ResetPassword::run($data);
        $this->assertIsString($response);
        Event::assertDispatched(PasswordReset::class);
    }
}
