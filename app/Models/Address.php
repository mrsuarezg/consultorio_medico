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

final class Address extends Model
{
    use HasColumnListing;
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'street',
        'inner_number',
        'outer_number',
        'city',
        'state',
        'country',
        'postal_code',
        'type_id',
        'person_id',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
