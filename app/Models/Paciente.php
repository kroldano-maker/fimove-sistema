<?php

namespace App\Models;

use App\Models\Concerns\Bitacorable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use HasFactory;
    use Bitacorable;

    protected $fillable = [
        'user_id',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'genero',
        'estado_civil',
        'profesion',
        'ocupacion',
        'lateralidad',
        'telefono',
        'direccion',
        'correo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_registro' => 'datetime',
        'fecha_alta' => 'date',
    ];

    // El usuario (personal administrativo) que registró a este paciente
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Un paciente tiene un único expediente clínico (1 a 1)
    public function expedienteClinico(): HasOne
    {
        return $this->hasOne(ExpedienteClinico::class);
    }

    // Un paciente tiene muchas citas (1 a *)
    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    // Un paciente tiene muchos registros de tratamiento (1 a *)
    public function tratamientos(): HasMany
    {
        return $this->hasMany(Tratamiento::class);
    }

     // Un paciente tiene muchas evaluaciones fisioterapéuticas (1 a *)
    public function evaluaciones(): HasMany
    {
        return $this->hasMany(EvaluacionFisioterapeutica::class)
            ->orderByDesc('fecha')
            ->orderByDesc('id');
    }

    // Estado del paciente según su tratamiento:
    // Alta, Activo, Abandonado o Sin tratamiento iniciado
    public function estadoTratamiento(): string
    {
        $ultimaSesion = $this->tratamientos->sortByDesc('fecha_registro')->first();

        if (!$ultimaSesion) {
            return 'Sin tratamiento iniciado';
        }

        $fechaUltimaSesion = Carbon::parse($ultimaSesion->fecha_registro);

        // Alta: el tratamiento se finalizó y no hay sesiones posteriores a esa fecha
        if ($this->fecha_alta && $fechaUltimaSesion->copy()->startOfDay()->lte($this->fecha_alta->copy()->startOfDay())) {
            return 'Alta';
        }

        $tieneCitaFutura = $this->citas
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->contains(function ($cita) {
                $fecha = Carbon::parse($cita->fecha);
                return $fecha->isToday() || $fecha->isFuture();
            });

        if ($fechaUltimaSesion->diffInDays(now()) <= 30 || $tieneCitaFutura) {
            return 'Activo';
        }

        return 'Abandonado';
    }

    public function etiquetaBitacora(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }
}