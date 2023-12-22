<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

namespace App\Traits\Models;

use Illuminate\Support\Facades\Schema;

trait HasColumnListing
{
    public static function getColumnListing(): array
    {
        return Schema::getColumnListing((new static())->getTable());
    }
}
