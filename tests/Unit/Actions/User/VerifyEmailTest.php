<?php

namespace Tests\Unit\Actions\User;

use App\Actions\User\VerifyEmail;
use App\Events\User\EmailVerified;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * @group user
 * @group user-unit
 * @group user-verify-email
 * @group user-verify-email-unit
 */
class VerifyEmailTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake(EmailVerified::class);
    }

    /** @test */
    public function it_can_verify_email_unit()
    {
        $user = User::factory()->create();

        $response = VerifyEmail::run($user, $user->email);
        $this->assertEquals($user->id, $response->id);
        Event::assertDispatched(EmailVerified::class);
    }
}
