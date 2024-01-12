<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

namespace App\Models;

use App\Traits\Models\HasColumnListing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Person extends Model
{
    use HasColumnListing;
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'personable_id',
        'personable_type',
        'person_type_id',
        'rfc',
    ];

    public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function phoneNumbers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PhoneNumber::class);
    }

    public function personable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
