<?php

namespace App\Actions\User;

use App\Actions\RouteAction;
use App\Events\User\ProfileUpdated;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\Actions\WithValidation;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;

class UpdateProfile extends RouteAction
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

        $user->update($attributes);

        ProfileUpdated::dispatch($user);

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
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstname' => ['sometimes', 'string', 'max:255'],
            'lastname' => ['sometimes', 'string', 'max:255'],
            //'email' => ['sometimes', 'email', 'max:255', 'unique:users'],
        ];
    }

    /**
     * @param ActionRequest $request
     *
     * @return JsonResponse
     */
    public function asController(ActionRequest $request): JsonResponse
    {
        $user = $this->handle($request->user(), $request->all());

        return response()->json(new UserResource($user));
    }
}
