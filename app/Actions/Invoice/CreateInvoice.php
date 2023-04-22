<?php

namespace App\Actions\Invoice;

use App\Actions\RouteAction;
use App\Events\Invoice\InvoiceCreated;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Traits\Actions\WithValidation;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;

class CreateInvoice extends RouteAction
{
    use WithValidation;

    /**
     * @param array $data
     * @param Maintenance $maintenance
     *
     * @return Invoice
     */
    public function handle(array $data, Maintenance $maintenance): Invoice
    {
        $this->fill($data);
        $attributes = $this->validated();

        $file = $attributes['file'];
        $disk = 's3';

        $path = Storage::disk($disk)->put('/', $file);

        $attributes = array_merge(
            Arr::except($attributes, ['file']),
            [
                'name' => !isset($attributes['name'])
                    ? $file->getClientOriginalName()
                    : $attributes['name'],
                'path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'disk' => $disk,
                'maintenance_id' => $maintenance->id,
                'vehicule_id' => $maintenance->vehicule_id,
            ]
        );

        $invoice = Invoice::create($attributes);

        InvoiceCreated::dispatch($invoice);

        return $invoice;
    }

    /**
     * @return Authenticatable
     */
    public function authorize(): Authenticatable
    {
        return auth()->user();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $isRoute = $this->isFromRoute();

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:10000'],
            'maintenance_uuid' => [Rule::requiredIf($isRoute), 'exists:maintenances,uuid'],
        ];
    }

    /**
     * @param ActionRequest $request
     *
     * @return JsonResponse
     */
    public function asController(ActionRequest $request): JsonResponse
    {
        $invoice = $this->handle(
            $request->all(),
            Maintenance::firstWhere('uuid', $request->maintenance_uuid),
        );

        return response()->json(new InvoiceResource($invoice), Response::HTTP_CREATED);
    }
}
