<?php

namespace App\Models;

use App\Traits\Models\HasColumnListing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class VitalSign extends Model
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
        'consultation_id',
        'blood_pressure', // Presión arterial
        'heart_rate', // Frecuencia cardiaca (pulso)
        'respiratory_rate', // Frecuencia respiratoria
        'body_temperature', // Temperatura corporal
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string>
     */
    protected $casts = [
        'consultation_id' => 'integer',
        'blood_pressure' => 'string', // Presión arterial (TA = Tensión arterial)
        'heart_rate' => 'integer', // Frecuencia cardiaca (pulso)
        'respiratory_rate' => 'integer', // Frecuencia respiratoria
        'body_temperature' => 'float', // Temperatura corporal
    ];

    public function consultation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }
}
