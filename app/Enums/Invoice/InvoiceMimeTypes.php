<?php

namespace App\Enums\Invoice;

enum InvoiceMimeTypes: string
{
    case Jpeg = 'image/jpeg';
    case Pdf = 'application/pdf';
    case Png = 'image/png';
}
