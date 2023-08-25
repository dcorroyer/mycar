<?php

namespace App\Actions\Invoice;

use App\Actions\RouteAction;
use App\Events\Invoice\InvoiceDownloaded;
use App\Exceptions\Invoice\InvalidInvoice;
use App\Helpers\UserHelper;
use App\Models\Invoice;
use App\Models\Maintenance;
use App\Models\Vehicule;
use App\Traits\Actions\WithValidation;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class DownloadInvoice extends RouteAction
{
    use WithValidation;

    /**
     * @param Invoice $invoice
     *
     * @return StreamedResponse
     */
    public function handle(Invoice $invoice): StreamedResponse
    {
        InvoiceDownloaded::dispatch($invoice);

        return Storage::disk($invoice->disk)->download($invoice->path, $invoice->file_name);
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
     * @return StreamedResponse
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function asController(Vehicule $vehicule, Maintenance $maintenance, Invoice $invoice): StreamedResponse
    {
        return $this->handle($invoice);
    }
}
