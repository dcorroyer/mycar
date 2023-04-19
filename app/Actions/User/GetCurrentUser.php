<?php

namespace App\Actions\User;

use App\Actions\RouteAction;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;

class GetCurrentUser extends RouteAction
{
    /**
     * @return User
     */
    public function handle(): User
    {
        if (!auth()->user()) {
            throw new UnauthorizedException();
        }

        return User::where('id', auth()->user()->id)->first();
    }

    /**
     * @return JsonResponse
     */
    public function asController(): JsonResponse
    {
        return response()
            ->json(new UserResource($this->handle()));
    }
}
