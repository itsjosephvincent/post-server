<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UsesUuid
{
    /**
     * The "booting" method of the model, This help to magically create uuid for all new models
     */
    public static function boot(): void
    {
        parent::boot();
        self::creating(function ($model): void {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
