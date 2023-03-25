<?php

namespace App\Actions\Vehicule;

use App\Enums\Vehicule\VehiculeTypes;
use App\Events\Vehicule\VehiculeCreated;
use App\Exceptions\Vehicule\InvalidVehicule;
use App\Http\Resources\VehiculeResource;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

class CreateVehicule extends Action
{
    use WithValidation;

    /**
     * @param array $data
     *
     * @return Vehicule
     *
     * @throws Throwable
     */
    public function handle(array $data): Vehicule
    {
        throw_if(
            Vehicule::where('identification', $data['identification'])->first(),
            InvalidVehicule::class,
            'Vehicule already exists',
        );

        $this->fill($data);
        $attributes = $this->validated();

        $vehicule = Vehicule::create($attributes);

        VehiculeCreated::dispatch($vehicule);

        return $vehicule;
    }

    /**
     * @param ActionRequest $request
     *
     * @return bool
     */
    public function authorize(ActionRequest $request): bool
    {
        return auth()->user()->id === $request->user_id;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', new Enum(VehiculeTypes::class)],
            'identification' => ['required', 'string', 'max:255', 'unique:vehicules'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'modelyear' => ['required', 'numeric'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    /**
     * @param ActionRequest $request
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function asController(ActionRequest $request): JsonResponse
    {
        $vehicule = $this->handle($request->all());

        return response()->json(new VehiculeResource($vehicule), Response::HTTP_CREATED);
    }
}
