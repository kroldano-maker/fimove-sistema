<?php

namespace App\Models;

use App\Models\Concerns\Bitacorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    use HasFactory;
    use Bitacorable;

    protected $fillable = [
        'paciente_id',
        'user_id',
        'fecha',
        'hora',
        'motivo',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // La cita pertenece a un paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    // La cita pertenece a un usuario (quien la agenda o atiende)
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function etiquetaBitacora(): string
    {
        $paciente = $this->paciente;
        $nombrePaciente = $paciente ? "{$paciente->nombres} {$paciente->apellidos}" : 'paciente eliminado';

        return "{$nombrePaciente} — {$this->fecha} {$this->hora}";
    }
}