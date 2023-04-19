<?php

namespace Tests\Unit\Actions\User;

use App\Actions\User\UpdatePassword;
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
    /** @test */
    public function it_can_update_password_unit()
    {
        $user = User::factory()->create();

        $data = [
            'current_password' => 'password',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $this->actingAs($user);
        $response = UpdatePassword::run($user, $data);
        $this->assertEquals($user->id, $response->id);
    }
}
