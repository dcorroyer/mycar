<?php

namespace App\Actions\User;

use App\Actions\RouteAction;
use App\Events\User\PasswordUpdated;
use App\Models\User;
use App\Traits\Actions\WithValidation;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;

class UpdatePassword extends RouteAction
{
    use WithValidation;

    /**
     * @param User $user
     * @param array $request
     *
     * @return User
     */
    public function handle(User $user, array $request): User
    {
        $this->fill($request);
        $attributes = $this->validated();

        $user->update([
            'password' => Hash::make($attributes['password']),
        ]);

        PasswordUpdated::dispatch($user);

        return $user;
    }

    /**
     * @return Authenticatable
     */
    public function authorize(): Authenticatable
    {
        return auth()->user();
    }

    /**
     * @param Validator $validator
     * @param ActionRequest $request
     *
     * @return void
     */
    public function afterValidator(Validator $validator, ActionRequest $request): void
    {
        if (!Hash::check($request->get('current_password'), $request->user()->password)) {
            $validator->errors()->add('current_password', 'Wrong password.');
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $isRoute = $this->isFromRoute();

        return [
            'current_password' => Rule::requiredIf($isRoute),
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * @param ActionRequest $request
     *
     * @return JsonResponse
     */
    public function asController(ActionRequest $request): JsonResponse
    {
        $this->handle($request->user(), $request->all());

        return response()->json();
    }
}
