<?php

namespace Tests\Unit\Actions\User;

use App\Actions\User\UpdateProfile;
use App\Events\User\ProfileUpdated;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group user
 * @group user-unit
 * @group user-update
 * @group user-update-unit
 */
class UpdateProfileTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(ProfileUpdated::class);
    }

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
        Event::assertDispatched(ProfileUpdated::class);
    }
}
