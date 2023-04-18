<?php

namespace App\Actions\Vehicule;

use App\Actions\RouteAction;
use App\Enums\Vehicule\VehiculeTypes;
use App\Events\Vehicule\VehiculeCreated;
use App\Exceptions\User\InvalidUser;
use App\Exceptions\Vehicule\InvalidVehicule;
use App\Http\Resources\VehiculeResource;
use App\Models\User;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

class CreateVehicule extends RouteAction
{
    use WithValidation;

    /**
     * @param array $data
     * @param User $user
     *
     * @return Vehicule
     *
     * @throws Throwable
     */
    public function handle(array $data, User $user): Vehicule
    {
        throw_if(
            Vehicule::where('identification', $data['identification'])->first(),
            InvalidVehicule::class,
            'Vehicule already exists',
        );

        $this->fill($data);
        $attributes = $this->validated();

        $attributes['user_id'] = $user->id;

        $vehicule = Vehicule::create($attributes);

        VehiculeCreated::dispatch($vehicule);

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
            !User::where('uuid', $request->get('user_uuid'))->exists(),
            InvalidUser::class,
            'User you sent doesn\'t exists',
        );

        return auth()->user()->id === User::firstWhere('uuid', $request->get('user_uuid'))->id;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $isRoute = $this->isFromRoute();

        return [
            'type' => ['required', 'string', new Enum(VehiculeTypes::class)],
            'identification' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'modelyear' => ['required', 'numeric'],
            'user_uuid' => [Rule::requiredIf($isRoute), 'exists:users,uuid'],
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
        $vehicule = $this->handle(
            $request->all(),
            User::firstWhere('uuid', $request->user_uuid),
        );

        return response()->json(new VehiculeResource($vehicule), Response::HTTP_CREATED);
    }
}
