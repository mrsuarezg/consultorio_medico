<?php

namespace App\Traits\Models;

use Illuminate\Support\Facades\Schema;

trait HasColumnListing
{
    public static function getColumnListing(): array
    {
        return Schema::getColumnListing((new static())->getTable());
    }
}
