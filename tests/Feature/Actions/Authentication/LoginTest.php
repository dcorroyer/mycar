<?php

namespace Tests\Feature\Actions\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @group login
 * @group login-feature
 */
class LoginTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'email' => 'john@mycar.local',
            'password' => Hash::make('password'),
        ]);

        $this->user = [
            'email' => 'john@mycar.local',
            'password' => 'password',
        ];

        $this->route = route('login');
    }

    /** @test */
    public function it_can_login_feature()
    {
        $this->postJson($this->route, $this->user)
            ->assertOk();
    }

    /** @test */
    public function it_cant_login_invalid_credentials_feature()
    {
        $this->user['password'] = 'john123';

        $this->postJson($this->route, $this->user)
            ->assertUnprocessable();
    }
}
