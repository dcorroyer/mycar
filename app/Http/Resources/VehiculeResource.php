<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehiculeResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'type' => $this->type,
            'identification' => $this->identification,
            'brand' => $this->brand,
            'model' => $this->model,
            'modelyear' => $this->modelyear,
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            'maintenances' => $this->whenLoaded('maintenances', function () {
                return MaintenanceResource::collection($this->maintenances);
            }),
        ];
    }
}
