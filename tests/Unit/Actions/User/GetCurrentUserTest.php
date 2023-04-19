<?php

namespace Tests\Unit\Actions\User;

use App\Actions\User\GetCurrentUser;
use App\Models\User;
use Illuminate\Validation\UnauthorizedException;
use Tests\TestCase;

/**
 * @group user
 * @group user-unit
 * @group user-current
 * @group user-current-unit
 */
class GetCurrentUserTest extends TestCase
{
    /** @test */
    public function it_can_get_current_user_unit()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = GetCurrentUser::run($user);
        $this->assertEquals($user->id, $response->id);
    }
}
