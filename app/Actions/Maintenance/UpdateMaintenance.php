<?php

namespace App\Actions\Maintenance;

use App\Actions\RouteAction;
use App\Enums\Maintenance\MaintenanceTypes;
use App\Events\Maintenance\MaintenanceUpdated;
use App\Exceptions\Maintenance\InvalidMaintenance;
use App\Helpers\UserHelper;
use App\Http\Resources\MaintenanceResource;
use App\Models\Maintenance;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

class UpdateMaintenance extends RouteAction
{
    use WithValidation;

    /**
     * @param Maintenance $maintenance
     * @param array $data
     *
     * @return Maintenance
     *
     * @throws Throwable
     */
    public function handle(Maintenance $maintenance, array $data): Maintenance
    {
        $this->fill($data);
        $attributes = $this->validated();

        $maintenance->update($attributes);

        MaintenanceUpdated::dispatch($maintenance);

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
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'string', new Enum(MaintenanceTypes::class)],
            'date' => ['sometimes', 'date', 'date_format:Y-m-d'],
            'amount' => ['sometimes', 'numeric'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }

    /**
     * @param Vehicule $vehicule
     * @param Maintenance $maintenance
     * @param ActionRequest $request
     *
     * @return JsonResponse
     *
     * @throws Throwable
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function asController(Vehicule $vehicule, Maintenance $maintenance, ActionRequest $request): JsonResponse
    {
        $maintenance = $this->handle(
            $maintenance,
            $request->all(),
        );

        return response()->json(new MaintenanceResource($maintenance));
    }
}
