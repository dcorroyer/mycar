<?php

namespace App\Actions\Invoice;

use App\Actions\RouteAction;
use App\Events\Invoice\InvoiceDeleted;
use App\Exceptions\Invoice\InvalidInvoice;
use App\Helpers\UserHelper;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use Throwable;

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
     * @param ActionRequest $request
     *
     * @return bool
     *
     * @throws Throwable
     */
    public function authorize(ActionRequest $request): bool
    {
        throw_if(
            !Invoice::where('uuid', $request->invoice->uuid)->exists(),
            InvalidInvoice::class,
            'Invoice you sent doesn\'t exists',
        );

        return auth()->user()
            && app(UserHelper::class)->isVehiculeFromUser(
                auth()->user(),
                Maintenance::firstWhere('id', $request->invoice->maintenance_id)
            );
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
