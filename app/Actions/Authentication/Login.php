<?php

namespace App\Actions\Authentication;

use App\Exceptions\Authentication\InvalidAuthentication;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

class Login extends Action
{
    use WithValidation;

    /**
     * @param array $data
     *
     * @return string
     *
     * @throws Throwable
     */
    public function handle(array $data): string
    {
        $this->fill($data);
        $attributes = $this->validated();

        throw_if(
            !Auth::attempt($attributes),
            InvalidAuthentication::class,
            'Invalid credentials'
        );

        $user = Auth::authenticate();

        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', Password::min(8)],
        ];
    }

    /**
     * @param ActionRequest $request
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function asController(ActionRequest $request): JsonResponse
    {
        $token = $this->handle($request->all());

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
