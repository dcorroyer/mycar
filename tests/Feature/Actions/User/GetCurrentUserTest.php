<?php

namespace Tests\Feature\Actions\User;

use App\Models\User;
use Tests\TestCase;

/**
 * @group user
 * @group user-feature
 * @group user-current
 * @group user-current-feature
 */
class GetCurrentUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->route = route('user.me');
    }

    /** @test */
    public function it_can_get_current_user_feature()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->getJson($this->route)
            ->assertOk()
            ->assertJsonFragment(['email' => $user->email]);
    }

    /** @test */
    public function it_cannot_get_current_user_if_not_logged_feature()
    {
        User::factory()->create();

        $this->getJson($this->route)
            ->assertUnauthorized();
    }
}
