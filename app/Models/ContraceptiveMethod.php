<?php

namespace App\Models;

use App\Traits\Models\HasColumnListing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ContraceptiveMethod extends Model
{
    use HasColumnListing;
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "contraceptive_methods";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'observations',
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'observations' => 'string',
        'name' => 'string',
        'description' => 'string',
    ];
}
