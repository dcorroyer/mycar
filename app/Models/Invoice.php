<?php

namespace App\Models;

use App\Traits\Models\HasUlid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Invoice extends Model
{
    use HasFactory;
    use HasUlid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'file_name',
        'mime_type',
        'path',
        'disk',
        'size',
        'position',
        'maintenance_id',
    ];

    /**
     * Get the maintenance of the invoice
     *
     * @return BelongsTo
     */
    public function maintenance(): BelongsTo
    {
        return $this->belongsTo(Maintenance::class);
    }

    /**
     * Generate URL.
     *
     * @return Attribute
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk('s3')->url(
                $this->getAttribute('path'),
            )
        );
    }
}
