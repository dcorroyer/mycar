<?php

namespace App\Models;

use App\Traits\Models\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Maintenance extends Model
{
    use HasFactory;
    use HasUlid;

    /**
     * The attributes that are castable.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'date',
        'amount',
        'description',
        'vehicule_id',
    ];

    /**
     * Get the vehicule of the maintenance
     *
     * @return BelongsTo
     */
    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Get the invoices for the maintenance
     *
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
