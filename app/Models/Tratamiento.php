<?php

namespace App\Models;

use App\Models\Concerns\Bitacorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tratamiento extends Model
{
    use HasFactory;
    use Bitacorable;

    protected $fillable = [
        'paciente_id',
        'user_id',
        'descripcion',
        'sesion',
        'evolucion',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    // El tratamiento (sesión) pertenece a un paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    // El tratamiento (sesión) fue registrado por un fisioterapeuta
    public function fisioterapeuta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function etiquetaBitacora(): string
    {
        $paciente = $this->paciente;
        $nombrePaciente = $paciente ? "{$paciente->nombres} {$paciente->apellidos}" : 'paciente eliminado';

        return "Sesión {$this->sesion} — {$nombrePaciente}";
    }
}