<?php

namespace App\Actions\User;

use App\Actions\RouteAction;
use App\Events\User\PasswordReset;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\ActionRequest;

class ResetPassword extends RouteAction
{
    use WithValidation;

    /**
     * @param array $request
     *
     * @return mixed
     */
    public function handle(array $request): mixed
    {
        $this->fill($request);
        $attributes = $this->validated();

        return Password::reset(
            Arr::only($attributes, ['email', 'password', 'password_confirmation', 'token']),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                PasswordReset::dispatch($user);
            }
        );
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ];
    }

    /**
     * @param ActionRequest $request
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     */
    public function asController(ActionRequest $request): JsonResponse
    {
        $status = $this->handle($request->all());

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)])
            : throw ValidationException::withMessages(['email' => __($status)]);
    }
}
