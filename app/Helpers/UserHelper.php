<?php

namespace App\Helpers;

use App\Models\Vehicule;
use Illuminate\Contracts\Auth\Authenticatable;

class UserHelper
{
    /**
     * @param Authenticatable $user
     * @param Vehicule $vehicule
     *
     * @return bool
     */
    public function isVehiculeFromUser(Authenticatable $user, Vehicule $vehicule): bool
    {
        return $user->id === $vehicule->user_id;
    }
}
