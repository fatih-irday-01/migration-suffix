<?php

namespace Fatihirday\Suffixed\Models;

use Fatihirday\Suffixed\Migrate\SuffixMigrate;

trait SuffixList
{
    public static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            if (config('suffixed.suffixes.auto_create_suffixed_tables')) {
                SuffixMigrate::createSuffixedTables(
                    $item[config('suffixed.suffixes.column')]
                );
            }
        });
    }
}
