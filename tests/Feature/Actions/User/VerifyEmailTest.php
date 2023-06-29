<?php

namespace Tests\Feature\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/**
 * @group user
 * @group user-feature
 * @group user-verify-email
 * @group user-verify-email-feature
 */
class VerifyEmailTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $secret = Crypt::encrypt([
            'user_id' => $this->user->id,
            'email' => $this->user->email,
        ]);

        $this->link = URL::signedRoute(
            'user.verify-email',
            [
                'user' => $this->user->uuid,
                'secret' => $secret,
            ]
        );
    }

    /** @test */
    public function it_can_verify_email_feature()
    {
        $this->actingAs($this->user)
            ->getJson($this->link)
            ->assertOk();
    }
}
