<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
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
            'date' => $this->date->format('Y-m-d'),
            'amount' => $this->amount,
            'description' => $this->description,
            'vehicule' => $this->whenLoaded('vehicule', fn () => new VehiculeResource($this->vehicule)),
            'invoices' => $this->whenLoaded('invoices', function () {
                return InvoiceResource::collection($this->invoices);
            }),
        ];
    }
}
