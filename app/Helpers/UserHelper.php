<?php

namespace App\Helpers;

use App\Models\Maintenance;
use App\Models\Vehicule;
use Illuminate\Contracts\Auth\Authenticatable;

class UserHelper
{
    /**
     * @param Authenticatable $user
     * @param Maintenance|Vehicule $attribute
     *
     * @return bool
     */
    public function isVehiculeFromUser(Authenticatable $user, Maintenance|Vehicule $attribute): bool
    {
        if (isset($attribute->vehicule_id)) {
            $attribute = Vehicule::firstWhere('id', $attribute->vehicule_id);
        }

        return $user->id === $attribute->user_id;
    }
}
