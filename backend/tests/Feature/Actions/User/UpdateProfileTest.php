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
class UpdateProfileTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->route = route('user.update');
    }

    /** @test */
    public function it_can_update_profile_feature()
    {
        $data = [
            'firstname' => 'John',
            'lastname' => 'Doe',
        ];

        $this->actingAs($this->user)
            ->patchJson($this->route, $data)
            ->assertOk()
            ->assertJsonFragment(['firstname' => $data['firstname']]);
    }
}
