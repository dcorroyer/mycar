<?php

namespace App\Actions\Vehicule;

use App\Enums\Vehicule\VehiculeTypes;
use App\Events\Vehicule\VehiculeCreated;
use App\Http\Resources\VehiculeResource;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;

class CreateVehicule extends Action
{
    use WithValidation;

    /**
     * @param array $data
     *
     * @return \App\Models\Vehicule
     */
    public function handle(array $data): Vehicule
    {
        $this->fill($data);
        $attributes = $this->validated();

        $vehicule = Vehicule::create($attributes);

        VehiculeCreated::dispatch($vehicule);

        return $vehicule;
    }

    /**
     * @param \Lorisleiva\Actions\ActionRequest $request
     *
     * @return bool
     */
    public function authorize(ActionRequest $request): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'type'           => ['required', 'string', new Enum(VehiculeTypes::class)],
            'identification' => ['required', 'string', 'max:255'],
            'brand'          => ['required', 'string', 'max:255'],
            'model'          => ['required', 'string', 'max:255'],
            'modelyear'      => ['required', 'numeric'],
            //'user_uuid' => ['required', 'exists:users,uuid', 'string'],
        ];
    }

    /**
     * @param \Lorisleiva\Actions\ActionRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function asController(ActionRequest $request): JsonResponse
    {
        $vehicule = $this->handle($request->all());

        return response()->json(new VehiculeResource($vehicule), Response::HTTP_CREATED);
    }
}
