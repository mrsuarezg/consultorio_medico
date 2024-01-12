<?php

namespace App\Models;

use App\Traits\Models\HasColumnListing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class GynecologicalObstetricPregnancies extends Model
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
        'menarche', // Menarca
        'IVSA', // IVSA (Inicio de vida sexual activa)
        'number_of_partners', // Número de parejas
        'pregnancies_G_P_C_A_O', // Embarazos G, P, C, A, O (Gestaciones, Partos, Cesáreas, Abortos, Obitos)
        'contraceptive_method_id', // Método anticonceptivo
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string>
     */
    protected $casts = [
        'patient_id' => 'integer',
        'menarche' => 'string',
        'IVSA' => 'string',
        'number_of_partners' => 'string',
        'pregnancies_G_P_C_A_O' => 'string',
        'contraceptive_method_id' => 'integer',
    ];

    /**
     * Get the contraceptiveMethod that owns the GynecologicalObstetricPregnancies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function contraceptiveMethod(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ContraceptiveMethod::class);
    }

    /**
     * Get the patient that owns the GynecologicalObstetricPregnancies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
