<?php

namespace App\Models;

use App\Traits\Models\HasColumnListing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Consultation extends Model
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
        'doctor_id',
        'diagnosis', // Diagnóstico
        'observations', // Observaciones
        'symptoms', // Síntomas
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string>
     */
    protected $casts = [
        'patient_id' => 'integer',
        'doctor_id' => 'integer',
        'diagnosis' => 'string', // Diagnóstico
        'observations' => 'string', // Observaciones
        'symptoms' => 'string', // Síntomas
    ];

    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicalPrescription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MedicalPrescription::class);
    }

    public function somatometry(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Somatometry::class);
    }

    public function vitalSign(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(VitalSign::class);
    }
}
