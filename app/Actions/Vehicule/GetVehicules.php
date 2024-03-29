<?php

namespace App\Actions\Vehicule;

use App\Contracts\Actions\QueryBuilderAction;
use App\Http\Resources\VehiculeResource;
use App\Models\Vehicule;
use App\Traits\Actions\WithQueryBuilder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;

class GetVehicules extends Action implements QueryBuilderAction
{
    use WithQueryBuilder;

    /**
     * @param array $query
     * @param Vehicule|null $vehicule
     *
     * @return Collection
     */
    public function handle(array $query, ?Vehicule $vehicule): Collection
    {
        return $this->getQueryBuilder(Vehicule::class, $query)
            ->when(
                $vehicule->id,
                fn ($q) => $q->where('id', $vehicule->id)
            )
            ->when(
                auth()->user(),
                fn ($q) => $q->whereHas(
                    'user',
                    fn ($q) => $q->where('users.id', auth()->user()->id)
                )
            )
            ->get();
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return [
            'type',
            'identification',
            'brand',
            'model',
            'modelyear',
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            'type',
            'identification',
            'brand',
            'modelyear',
        ];
    }

    /**
     * @return array
     */
    public function getIncludes(): array
    {
        return [
            'user',
            'maintenances',
            'invoices',
        ];
    }

    /**
     * @return array
     */
    public function getSorts(): array
    {
        return [
            'type',
            'brand',
            'model',
            'modelyear',
        ];
    }

    /**
     * @return Authenticatable
     */
    public function authorize(): Authenticatable
    {
        return auth()->user();
    }

    /**
     * @param Vehicule $vehicule
     * @param ActionRequest $request
     *
     * @return JsonResponse
     */
    public function asController(Vehicule $vehicule, ActionRequest $request): JsonResponse
    {
        $vehicules = $this->handle($request->all(), $vehicule);

        if (!$vehicule->id) {
            return response()
                ->json(VehiculeResource::collection($vehicules));
        }

        $vehicule = $vehicules->first();

        return $vehicule
            ? response()->json(new VehiculeResource($vehicule))
            : response()->json([], Response::HTTP_NOT_FOUND);
    }
}
