<?php

namespace App\Actions\Authentication;

use App\Events\User\UserCreated;
use App\Exceptions\User\InvalidUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

class Register extends Action
{
    use WithValidation;

    /**
     * @param array $data
     *
     * @return User
     *
     * @throws Throwable
     */
    public function handle(array $data): User
    {
        throw_if(
            User::where('email', $data['email'])->first(),
            InvalidUser::class,
            'User already exists'
        );

        $this->fill($data);
        $attributes = $this->validated();

        $attributes['password'] = Hash::make($attributes['password']);

        $user = User::create($attributes);

        $user->remember_token = Str::random(60);
        $user->save();

        UserCreated::dispatch($user);

        return $user;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
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
        $user = $this->handle($request->all());

        return response()->json(new UserResource($user), Response::HTTP_CREATED);
    }
}
