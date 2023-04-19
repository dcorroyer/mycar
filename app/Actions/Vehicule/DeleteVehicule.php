<?php

namespace App\Actions\Vehicule;

use App\Events\Vehicule\VehiculeDeleted;
use App\Exceptions\Vehicule\InvalidVehicule;
use App\Helpers\UserHelper;
use App\Models\Vehicule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

class DeleteVehicule extends Action
{
    /**
     * @param Vehicule $vehicule
     *
     * @return Vehicule
     *
     * @throws Throwable
     */
    public function handle(Vehicule $vehicule): Vehicule
    {
        $vehicule->delete();

        VehiculeDeleted::dispatch($vehicule);

        return $vehicule;
    }

    /**
     * @param ActionRequest $request
     *
     * @return bool
     *
     * @throws Throwable
     */
    public function authorize(ActionRequest $request): bool
    {
        throw_if(
            !Vehicule::where('uuid', $request->vehicule->uuid)->exists(),
            InvalidVehicule::class,
            'Vehicule not found',
        );

        return auth()->user()
            && app(UserHelper::class)->isVehiculeFromUser(
                auth()->user(),
                Vehicule::firstWhere('uuid', $request->vehicule->uuid)
            );
    }

    /**
     * @param Vehicule $vehicule
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function asController(Vehicule $vehicule): JsonResponse
    {
        $this->handle($vehicule);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
