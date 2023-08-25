<?php

namespace App\Actions\Invoice;

use App\Contracts\Actions\QueryBuilderAction;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\Vehicule;
use App\Traits\Actions\WithQueryBuilder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;

class GetInvoices extends Action implements QueryBuilderAction
{
    use WithQueryBuilder;

    /**
     * @param array $query
     * @param Maintenance $maintenance
     * @param Invoice|null $invoice
     *
     * @return Collection
     */
    public function handle(array $query, Maintenance $maintenance, ?Invoice $invoice): Collection
    {
        return $this->getQueryBuilder(Invoice::class, $query)
            ->when(
                $invoice->id,
                fn ($q) => $q->where('id', $invoice->id)
            )
            ->when(
                $maintenance->id,
                fn ($q) => $q->where('maintenance_id', $maintenance->id)
            )
            ->get();
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return [
            'name',
            'mime_type',
            'path',
            'disk',
            'size',
            'position',
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            'name',
            'mime_type',
            'path',
        ];
    }

    /**
     * @return array
     */
    public function getIncludes(): array
    {
        return [
            'maintenance',
        ];
    }

    /**
     * @return array
     */
    public function getSorts(): array
    {
        return [
            'mime_type',
            'size',
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
     * @param Invoice $invoice
     *
     * @return JsonResponse
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function asController(
        ActionRequest $request,
        Vehicule $vehicule,
        Maintenance $maintenance,
        Invoice $invoice
    ): JsonResponse {
        $invoices = $this->handle(
            $request->all(),
            $maintenance,
            $invoice,
        );

        if (!$invoice->id) {
            return response()
                ->json(InvoiceResource::collection($invoices));
        }

        $invoice = $invoices->first();

        return $invoice
            ? response()->json(new InvoiceResource($invoice))
            : response()->json([], Response::HTTP_NOT_FOUND);
    }
}
