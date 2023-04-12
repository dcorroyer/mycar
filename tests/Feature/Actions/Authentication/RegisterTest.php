<?php

namespace Tests\Feature\Actions\Authentication;

use App\Actions\Authentication\Register;
use App\Events\User\UserCreated;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group register
 * @group register-feature
 */
class RegisterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@mycar.local',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->route = route('register');
    }

    /** @test */
    public function it_can_register_feature()
    {
        $this->postJson($this->route, $this->user)
            ->assertCreated()
            ->assertJson([
                'email' => $this->user['email'],
            ])
        ;
    }

    /** @test */
    public function it_cant_register_bad_request_feature()
    {
        $this->user['email'] = 'john';

        $this->postJson($this->route, $this->user)
            ->assertUnprocessable();
    }
}
