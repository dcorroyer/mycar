<?php

namespace App\Actions\User;

use App\Actions\RouteAction;
use App\Events\User\UserCreated;
use App\Models\User;
use App\Notifications\User\VerifyEmail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class SendVerifyEmailNotification extends RouteAction
{
    public function handle(User $user, string $email): void
    {
        $secret = Crypt::encrypt([
            'user_id' => $user->id,
            'email' => $email,
        ]);

        $link = URL::signedRoute(
            'user.verify-email',
            [
                'user' => $user->uuid,
                'secret' => $secret,
            ]
        );

        $user->notifyNow(new VerifyEmail($link));
    }

    public function asListener(UserCreated $event): void
    {
        $this->handle($event->getUser(), $event->getEmail());
    }
}
