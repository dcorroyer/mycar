<?php

namespace Tests\Unit\Actions\Authentication;

use App\Actions\Authentication\Register;
use App\Events\User\UserCreated;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group register
 * @group register-unit
 */
class RegisterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(UserCreated::class);
    }

    /** @test */
    public function it_can_register_unit()
    {
        $user = Register::run([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@mycar.local',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);

        Event::assertDispatched(UserCreated::class);
    }
}
