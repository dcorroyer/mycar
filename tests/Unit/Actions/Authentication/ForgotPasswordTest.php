<?php

namespace Tests\Unit\Actions\Authentication;

use App\Actions\Authentication\ForgotPassword;
use App\Events\User\ForgotPasswordRequested;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group user
 * @group user-unit
 * @group user-forgot-password
 * @group user-forgot-password-unit
 */
class ForgotPasswordTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(ForgotPasswordRequested::class);

        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_send_reset_password_unit()
    {
        $status = ForgotPassword::run($this->user->email);

        $this->assertIsString($status);
        Event::assertDispatched(ForgotPasswordRequested::class);
    }
}
