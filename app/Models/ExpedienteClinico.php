<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpedienteClinico extends Model
{
    use HasFactory;

    protected $table = 'expedientes_clinicos';

    protected $fillable = [
        'paciente_id',
        'antecedentes',
        'diagnostico',
        'observaciones',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];

    // El expediente pertenece a un único paciente (1 a 1, visto desde este lado)
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }
}