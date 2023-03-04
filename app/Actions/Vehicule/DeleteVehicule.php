<?php

namespace App\Actions\Vehicule;

use App\Events\Vehicule\VehiculeDeleted;
use App\Models\Vehicule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Lorisleiva\Actions\Action;

class DeleteVehicule extends Action
{
    /**
     * @param \App\Models\Vehicule $vehicule
     *
     * @return \App\Models\Vehicule
     */
    public function handle(Vehicule $vehicule): Vehicule
    {
        $vehicule->delete();

        VehiculeDeleted::dispatch($vehicule);

        return $vehicule;
    }

    /**
     * @param \App\Models\Vehicule $vehicule
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function asController(Vehicule $vehicule): JsonResponse
    {
        $this->handle($vehicule);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
