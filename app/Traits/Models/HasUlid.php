<?php

namespace App\Traits\Models;

use Symfony\Component\Uid\Ulid;

trait HasUlid
{
    public static function bootHasUlid(): void
    {
        static::creating(function ($model) {
            $model->uuid = Ulid::generate();
        });
    }
}
