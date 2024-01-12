<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

namespace App\Models;

use App\Traits\Models\HasColumnListing;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class PhysicalPerson extends Model
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
        'name',
        'last_name',
        'surname',
        'gender',
        'birth_date',
        'civil_status',
        'religion',
    ];

    // // // full_name is a virtual attribute
    protected $appends = ['fullname'];

    public function person(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Person::class, 'personable');
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->last_name} {$this->surname}";
        // return Attribute::make('fullname', function () {
        //     return "{$this->name} {$this->last_name} {$this->surname}";
        // });
    }
}
