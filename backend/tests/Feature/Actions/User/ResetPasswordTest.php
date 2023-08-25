<?php

namespace Tests\Feature\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

/**
 * @group user
 * @group user-feature
 * @group user-reset-password
 * @group user-reset-password-feature
 */
class ResetPasswordTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = Password::broker()->createToken($this->user);
        $this->password = 'password123';

        $this->route = route('password.reset');
    }

    /** @test */
    public function it_can_reset_password_feature()
    {
        $this->actingAs($this->user)
            ->postJson($this->route,
                [
                    'token' => $this->token,
                    'email' => $this->user->email,
                    'password' => $this->password,
                    'password_confirmation' => $this->password,
                ])
            ->assertOk()
            ->assertJsonStructure(['message']);
    }
}
