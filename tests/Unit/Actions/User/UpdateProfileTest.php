<?php

namespace Tests\Unit\Actions\User;

use App\Actions\User\UpdateProfile;
use App\Models\User;
use Tests\TestCase;

/**
 * @group user
 * @group user-feature
 * @group user-update
 * @group user-update-feature
 */
class UpdateProfileTest extends TestCase
{
    /** @test */
    public function it_can_update_profile_unit()
    {
        $user = User::factory()->create();

        $data = [
            'firstname' => 'John',
            'lastname' => 'Doe',
        ];

        $this->actingAs($user);
        $response = UpdateProfile::run($user, $data);
        $this->assertEquals($user->id, $response->id);
    }
}
