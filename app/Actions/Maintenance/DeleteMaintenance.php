<?php

namespace App\Actions\Maintenance;

use App\Actions\RouteAction;
use App\Events\Maintenance\MaintenanceDeleted;
use App\Exceptions\Maintenance\InvalidMaintenance;
use App\Helpers\UserHelper;
use App\Models\Maintenance;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

class DeleteMaintenance extends RouteAction
{
    use WithValidation;

    /**
     * @param Maintenance $maintenance
     *
     * @return Maintenance
     *
     * @throws Throwable
     */
    public function handle(Maintenance $maintenance): Maintenance
    {
        $maintenance->delete();

        MaintenanceDeleted::dispatch($maintenance);

        return $maintenance;
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
            !Maintenance::where('uuid', $request->maintenance->uuid)->exists(),
            InvalidMaintenance::class,
            'Maintenance not found',
        );

        return auth()->user()
            && app(UserHelper::class)->isVehiculeFromUser(
                auth()->user(),
                Vehicule::firstWhere('id', $request->maintenance->vehicule_id)
            );
    }

    /**
     * @param Vehicule $vehicule
     * @param Maintenance $maintenance
     *
     * @return JsonResponse
     *
     * @throws Throwable
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function asController(Vehicule $vehicule, Maintenance $maintenance): JsonResponse
    {
        $this->handle($maintenance);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
