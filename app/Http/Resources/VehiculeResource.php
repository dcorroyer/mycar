<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VehiculeResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'type'           => $this->type,
            'identification' => $this->identification,
            'brand'          => $this->brand,
            'model'          => $this->model,
            'modelyear'      => $this->modelyear,
        ];
    }
}
