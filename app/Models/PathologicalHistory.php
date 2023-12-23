<?php

namespace App\Models;

use App\Traits\Models\HasColumnListing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class PathologicalHistory extends Model
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
        'patient_id',
        'infectious_diseases', // Enfermedades infecto contagiosas
        'chronic_degenerative_diseases', // Enfermedades crónico degenerativas
        'allergies', // Alergias
        'surgical_interventions', // Intervenciones quirúrgicas
        'traumatism', // Traumatismos
        'transfusions', // Transfusiones
        'seizures', // Convulsiones
        'addictions', // Adicciones
        'hospitalizations', // Hospitalizaciones
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string>
     */
    protected $casts = [
        'patient_id' => 'integer',
        'infectious_diseases' => 'string',
        'chronic_degenerative_diseases' => 'string',
        'allergies' => 'string',
        'surgical_interventions' => 'string',
        'traumatism' => 'string',
        'transfusions' => 'string',
        'seizures' => 'string',
        'addictions' => 'string',
        'hospitalizations' => 'string',
    ];

    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
