<?php

namespace App\Actions\User;

use App\Actions\RouteAction;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class GetCurrentUser extends RouteAction
{
    /**
     * @return User
     */
    public function handle(): User
    {
        return User::where('id', auth()->user()->id)->first();
    }

    /**
     * @return Authenticatable
     */
    public function authorize(): Authenticatable
    {
        return auth()->user();
    }

    /**
     * @return JsonResponse
     */
    public function asController(): JsonResponse
    {
        return response()->json(new UserResource($this->handle()));
    }
}
