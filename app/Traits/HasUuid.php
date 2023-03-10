<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Setup model event hooks.
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Model $model): void {
            $model->uuid = Str::uuid()->toString();
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
