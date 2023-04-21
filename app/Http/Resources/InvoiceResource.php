<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'name' => $this->name,
            'file_name' => $this->file_name,
            'mime_type' => $this->mime_type,
            'url' => $this->url,
            'size' => $this->size,
            //'position' => $this->position,
            'maintenance' => $this
                ->whenLoaded(
                    'maintenance',
                    fn () => new MaintenanceResource($this->maintenance)
                ),
        ];
    }
}
