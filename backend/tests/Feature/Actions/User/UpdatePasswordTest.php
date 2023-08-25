<?php

namespace Tests\Feature\Actions\User;

use App\Models\User;
use Tests\TestCase;

/**
 * @group user
 * @group user-feature
 * @group user-update
 * @group user-update-feature
 */
class UpdatePasswordTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->route = route('user.update-password');
    }

    /** @test */
    public function it_can_update_password_feature()
    {
        $data = [
            'current_password' => 'password',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $this->actingAs($this->user)
            ->postJson($this->route, $data)
            ->assertOk();
    }

    /** @test */
    public function it_cant_update_password_confirmed_dont_match_feature()
    {
        $data = [
            'current_password' => 'password',
            'password' => 'password123',
            'password_confirmation' => 'password12',
        ];

        $this->actingAs($this->user)
            ->postJson($this->route, $data)
            ->assertUnprocessable();
    }

    /** @test */
    public function it_cant_update_password_current_password_invalid_feature()
    {
        $data = [
            'current_password' => 'passworddddd',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $this->actingAs($this->user)
            ->postJson($this->route, $data)
            ->assertUnprocessable();
    }
}
