<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait UseUuid
 * @package App\Traits
 */
trait UseUuid
{
    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = static::generateUUID();
            }
        });
    }

    /**
     * We can't be too careful to make sure things are as unique as we want
     * @return string
     */
    protected static function generateUUID(): string
    {
        $uuid = (string)Str::orderedUuid();
        if (static::where('uuid', $uuid)->count() > 0) {
            return static::generateUUID();
        }
        return $uuid;
    }
}
