<?php

namespace App\Models;

use App\Traits\Models\HasColumnListing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class MedicalPrescription extends Model
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
        'emission_date', // Fecha de emisión
        'indications', // Indicaciones TODO: Implementar detalles de Prescripción médica
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string>
     */
    protected $casts = [
        'consultation_id' => 'integer',
        'emission_date' => 'date', // Fecha de emisión
        'indications' => 'string', // Indicaciones TODO: Implementar detalles de Prescripción médica
    ];

    public function consultation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    // public function medicalPrescriptionDetails() // TODO: Implementar detalles de Prescripción médica
    // {
    //     return $this->hasMany(MedicalPrescriptionDetail::class);
    // }
}
