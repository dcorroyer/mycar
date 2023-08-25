<?php

namespace Tests\Unit\Actions\User;

use App\Actions\User\UpdatePassword;
use App\Events\User\PasswordUpdated;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group user
 * @group user-unit
 * @group user-update
 * @group user-update-unit
 */
class UpdatePasswordTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(PasswordUpdated::class);
    }

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
        Event::assertDispatched(PasswordUpdated::class);
    }
}
