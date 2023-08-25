<?php

namespace App\Actions\Authentication;

use App\Actions\RouteAction;
use App\Events\User\ForgotPasswordRequested;
use App\Models\User;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\ActionRequest;

class ForgotPassword extends RouteAction
{
    use WithValidation;

    /**
     * @param string $email
     *
     * @return string|null
     */
    public function handle(string $email): ?string
    {
        $user = User::firstWhere('email', $email);

        if ($user) {
            $status = Password::sendResetLink(['email' => $email]);

            ForgotPasswordRequested::dispatch($user);

            return $status;
        }

        return null;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
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
        $status = $this->handle($request->validated()['email']);

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : throw ValidationException::withMessages(['email' => __($status)]);
    }
}
