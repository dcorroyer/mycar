<?php

namespace App\Actions\User;

use App\Actions\RouteAction;
use App\Events\User\EmailVerified;
use App\Exceptions\User\InvalidUser;
use App\Models\User;
use App\Traits\Actions\WithValidation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class VerifyEmail extends RouteAction
{
    use WithValidation;

    /**
     * Handle verification of email.
     *
     * @param User $user
     * @param string $email
     *
     * @return User
     *
     * @throws \Throwable
     */
    public function handle(User $user, string $email): User
    {
        throw_if(
            $user->email !== $email,
            InvalidUser::class,
            'User does not match with email sent',
        );

        $user->markEmailAsVerified();
        $user->save();

        EmailVerified::dispatch($user);

        return $user;
    }

    /**
     * @return JsonResponse
     *
     * @throws ValidationException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface|\Throwable
     */
    public function asController(): JsonResponse
    {
        try {
            $attributes = Arr::wrap(Crypt::decrypt(request()->get('secret')));
            if (!request()->hasValidSignature() || !Arr::has($attributes, ['email', 'user_id'])) {
                throw new InvalidSignatureException();
            }
        } catch (Exception $e) {
            throw ValidationException::withMessages([$e->getMessage()]);
        }

        $this->handle(
            User::findOrFail($attributes['user_id']),
            $attributes['email'],
        );

        return response()->json();
    }
}
