<?php

namespace App\Actions\Vehicule;

use App\Enums\Vehicule\VehiculeTypes;
use App\Events\Vehicule\VehiculeUpdated;
use App\Exceptions\Vehicule\InvalidVehicule;
use App\Http\Resources\VehiculeResource;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

class UpdateVehicule extends Action
{
    use WithValidation;

    /**
     * @param Vehicule $vehicule
     * @param array $data
     *
     * @return Vehicule
     *
     * @throws Throwable
     */
    public function handle(Vehicule $vehicule, array $data): Vehicule
    {
        throw_if(
            !Vehicule::where('uuid', $vehicule->uuid)->exists(),
            InvalidVehicule::class,
            'Vehicule not found',
        );

        $this->fill($data);
        $attributes = $this->validated();

        $vehicule->update($attributes);

        VehiculeUpdated::dispatch($vehicule);

        return $vehicule;
    }

    /**
     * @param ActionRequest $request
     *
     * @return bool
     */
    public function authorize(ActionRequest $request): bool
    {
        return auth()->user()->id === $request->vehicule->user_id;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'string', new Enum(VehiculeTypes::class)],
            'identification' => ['sometimes', 'string', 'max:255', 'unique:vehicules'],
            'brand' => ['sometimes', 'string', 'max:255'],
            'model' => ['sometimes', 'string', 'max:255'],
            'modelyear' => ['sometimes', 'numeric'],
        ];
    }

    /**
     * @param Vehicule $vehicule
     * @param ActionRequest $request
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function asController(Vehicule $vehicule, ActionRequest $request): JsonResponse
    {
        $vehicule = $this->handle($vehicule, $request->all());

        return response()->json(new VehiculeResource($vehicule));
    }
}
