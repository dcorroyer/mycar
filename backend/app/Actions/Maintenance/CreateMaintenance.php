<?php

namespace App\Actions\Maintenance;

use App\Actions\RouteAction;
use App\Enums\Maintenance\MaintenanceTypes;
use App\Events\Maintenance\MaintenanceCreated;
use App\Exceptions\Vehicule\InvalidVehicule;
use App\Helpers\UserHelper;
use App\Http\Resources\MaintenanceResource;
use App\Models\Maintenance;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

class CreateMaintenance extends RouteAction
{
    use WithValidation;

    /**
     * @param array $data
     * @param Vehicule $vehicule
     *
     * @return Maintenance
     */
    public function handle(array $data, Vehicule $vehicule): Maintenance
    {
        $this->fill($data);
        $attributes = $this->validated();

        $attributes['vehicule_id'] = $vehicule->id;

        $maintenance = Maintenance::create($attributes);

        MaintenanceCreated::dispatch($maintenance);

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
            !Vehicule::where('uuid', $request->get('vehicule_uuid'))->exists(),
            InvalidVehicule::class,
            'Vehicule you sent doesn\'t exists',
        );

        return auth()->user()
            && app(UserHelper::class)->isVehiculeFromUser(
                auth()->user(),
                Vehicule::firstWhere('uuid', $request->get('vehicule_uuid'))
            );
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $isRoute = $this->isFromRoute();

        return [
            'type' => ['required', 'string', new Enum(MaintenanceTypes::class)],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'amount' => ['required', 'numeric'],
            'description' => ['sometimes', 'nullable', 'string'],
            'vehicule_uuid' => [Rule::requiredIf($isRoute), 'exists:vehicules,uuid'],
        ];
    }

    /**
     * @param ActionRequest $request
     *
     * @return JsonResponse
     */
    public function asController(ActionRequest $request): JsonResponse
    {
        $maintenance = $this->handle(
            $request->all(),
            Vehicule::firstWhere('uuid', $request->vehicule_uuid),
        );

        return response()->json(new MaintenanceResource($maintenance), Response::HTTP_CREATED);
    }
}
