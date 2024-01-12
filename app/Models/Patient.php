<?php

namespace App\Models;

use App\Traits\Models\HasColumnListing;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Patient extends Model
{
    use HasColumnListing;
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'person_id',
        'blood_type_id',
        'occupation',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string>
     */
    protected $casts = [
        'person_id' => 'integer',
        'blood_type_id' => 'integer',
        'occupation' => 'string',
    ];

    public function bloodType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BloodType::class);
    }

    public function gynecologicalObstetricPregnancies(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(GynecologicalObstetricPregnancies::class);
    }

    public function hereditaryFamilyHistory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HereditaryFamilyHistory::class);
    }

    public function nonPathologicalHistory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(NonPathologicalHistory::class);
    }

    public function pathologicalHistory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PathologicalHistory::class);
    }

    public function person(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    // Scope name, lastname and surname (fullName) like %$fullName% search
    public function scopeFullNameLike($query, $fullName): \Illuminate\Database\Eloquent\Builder
    {
        // search in person and person.person_type_id = 1, and the person in personable
        return $query->whereHas('person', function (Builder $query) use ($fullName) {
            $query->where('person_type_id', 1)
                ->whereHas('personable', function (Builder $query) use ($fullName) {
                    $query->whereRaw('CONCAT(name, " ", last_name, " ", surname) LIKE ?', ["%$fullName%"]);
                });
        });
    }
}
