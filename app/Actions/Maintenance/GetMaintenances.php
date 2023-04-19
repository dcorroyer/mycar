<?php

namespace App\Actions\Maintenance;

use App\Contracts\Actions\QueryBuilderAction;
use App\Http\Resources\MaintenanceResource;
use App\Models\Maintenance;
use App\Models\Vehicule;
use App\Traits\Actions\WithQueryBuilder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;

class GetMaintenances extends Action implements QueryBuilderAction
{
    use WithQueryBuilder;

    /**
     * @param array $query
     * @param Vehicule $vehicule
     * @param Maintenance|null $maintenance
     *
     * @return Collection
     */
    public function handle(array $query, Vehicule $vehicule, ?Maintenance $maintenance): Collection
    {
        return $this->getQueryBuilder(Maintenance::class, $query)
            ->when(
                $maintenance->id,
                fn ($q) => $q->where('id', $maintenance->id)
            )
            ->when(
                $vehicule->id,
                fn ($q) => $q->where('vehicule_id', $vehicule->id)
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
            'amount',
            'description',
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            'type',
            'amount',
        ];
    }

    /**
     * @return array
     */
    public function getIncludes(): array
    {
        return [
            'vehicule',
        ];
    }

    /**
     * @return array
     */
    public function getSorts(): array
    {
        return [
            'type',
            'amount',
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
     * @param ActionRequest $request
     * @param Vehicule $vehicule
     * @param Maintenance $maintenance
     *
     * @return JsonResponse
     */
    public function asController(ActionRequest $request, Vehicule $vehicule, Maintenance $maintenance): JsonResponse
    {
        $maintenances = $this->handle(
            $request->all(),
            $vehicule,
            $maintenance,
        );

        if (!$maintenance->id) {
            return response()
                ->json(MaintenanceResource::collection($maintenances));
        }

        $maintenance = $maintenances->first();

        return $maintenance
            ? response()->json(new MaintenanceResource($maintenance))
            : response()->json([], Response::HTTP_NOT_FOUND);
    }
}
