<?php

namespace App\Actions\Vehicule;

use App\Enums\Vehicule\VehiculeTypes;
use App\Events\Vehicule\VehiculeUpdated;
use App\Http\Resources\VehiculeResource;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;

class UpdateVehicule extends Action
{
    use WithValidation;

    /**
     * @param \App\Models\Vehicule $vehicule
     * @param array $data
     *
     * @return \App\Models\Vehicule
     */
    public function handle(Vehicule $vehicule, array $data): Vehicule
    {
        $this->fill($data);
        $attributes = $this->validated();

        $vehicule->update($attributes);

        VehiculeUpdated::dispatch($vehicule);

        return $vehicule;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'type'           => ['sometimes', 'string', new Enum(VehiculeTypes::class)],
            'identification' => ['sometimes', 'string', 'max:255'],
            'brand'          => ['sometimes', 'string', 'max:255'],
            'model'          => ['sometimes', 'string', 'max:255'],
            'modelyear'      => ['sometimes', 'numeric'],
            //'user_uuid' => ['required', 'exists:users,uuid', 'string'],
        ];
    }

    /**
     * @param \App\Models\Vehicule $vehicule
     * @param \Lorisleiva\Actions\ActionRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function asController(Vehicule $vehicule, ActionRequest $request): JsonResponse
    {
        $vehicule = $this->handle($vehicule, $request->all());

        return response()->json(new VehiculeResource($vehicule));
    }
}
