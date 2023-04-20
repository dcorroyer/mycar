<?php

namespace Tests\Feature\Actions\Authentication;

use App\Models\User;
use Tests\TestCase;

/**
 * @group user
 * @group user-feature
 * @group user-forgot-password
 * @group user-forgot-password-feature
 */
class ForgotPasswordTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->route = route('password.forgot');
    }

    /** @test */
    public function it_can_send_reset_password_feature()
    {
        $this->actingAs($this->user)
            ->postJson($this->route, ['email' => $this->user->email])
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    /** @test */
    public function it_cant_send_reset_password_bad_email_feature()
    {
        $this->actingAs($this->user)
            ->postJson($this->route, ['email' => 'invalid@mail.com'])
            ->assertUnprocessable()
            ->assertJsonStructure(['message']);
    }
}
