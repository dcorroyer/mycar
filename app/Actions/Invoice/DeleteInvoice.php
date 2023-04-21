<?php

namespace App\Actions\Invoice;

use App\Actions\RouteAction;
use App\Events\Invoice\InvoiceDeleted;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DeleteInvoice extends RouteAction
{
    use WithValidation;

    /**
     * @param Invoice $invoice
     *
     * @return Invoice
     */
    public function handle(Invoice $invoice): Invoice
    {
        Storage::disk('s3')->delete($invoice->path);

        $invoice->delete();

        InvoiceDeleted::dispatch($invoice);

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
     * @param Vehicule $vehicule
     * @param Maintenance $maintenance
     * @param Invoice $invoice
     *
     * @return JsonResponse
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function asController(Vehicule $vehicule, Maintenance $maintenance, Invoice $invoice): JsonResponse
    {
        $this->handle($invoice);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
