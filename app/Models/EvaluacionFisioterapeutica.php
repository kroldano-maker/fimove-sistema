<?php

namespace App\Models;

use App\Models\Concerns\Bitacorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionFisioterapeutica extends Model
{
    use Bitacorable;

    protected $table = 'evaluaciones_fisioterapeuticas';

    protected $guarded = ['id'];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    // Fisioterapeuta (o administrador) que realizó la evaluación
    public function evaluador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Secciones del formato, en el orden del documento.
     * campo => [etiqueta, filas]  (filas 0 = campo de una línea)
     */
    public static function secciones(): array
    {
        return [
            'Datos de la consulta' => [
                'remitido_por' => ['Remitido por', 0],
                'diagnostico_medico' => ['Diagnóstico médico', 2],
            ],
            'Antecedentes personales' => [
                'ant_patologicos' => ['Patológicos', 2],
                'ant_quirurgicos' => ['Quirúrgicos', 2],
                'ant_traumaticos' => ['Traumáticos', 2],
                'ant_farmacologicos' => ['Farmacológicos', 2],
                'ant_alergicos' => ['Alérgicos', 2],
                'ant_gineco_obstetricos' => ['Gineco-obstétricos', 2],
                'ant_otros' => ['Otros', 2],
            ],
            'Antecedentes familiares' => [
                'antecedentes_familiares' => ['Antecedentes familiares', 3],
            ],
            'Hábitos y costumbres' => [
                'fuma' => ['Fuma', 0],
                'alcohol' => ['Alcohol', 0],
            ],
            'Motivo de consulta' => [
                'motivo_consulta' => ['Motivo de consulta', 3],
            ],
            'Cronología de la patología' => [
                'cronologia_patologia' => ['Cronología de la patología', 4],
            ],
            'Dolor' => [
                'dolor' => ['Dolor', 3],
            ],
            'Palpación' => [
                'palpacion' => ['Palpación', 3],
            ],
            'Reflejos osteotendinosos' => [
                'reflejos_osteotendinosos' => ['Reflejos osteotendinosos', 3],
            ],
            'Sensibilidad' => [
                'sensibilidad' => ['Sensibilidad', 3],
            ],
            'Evaluación postural' => [
                'postura_anterior' => ['Vista anterior', 3],
                'postura_lateral' => ['Vista lateral', 3],
                'postura_posterior' => ['Vista posterior', 3],
            ],
            'Pruebas especiales' => [
                'pruebas_especiales' => ['Pruebas especiales', 3],
            ],
            'Longitud de miembros' => [
                'longitud_superiores' => ['Superiores', 0],
                'longitud_inferiores' => ['Inferiores', 0],
            ],
            'Examen muscular' => [
                'examen_muscular' => ['Examen muscular', 4],
            ],
            'Arco de movimiento articular' => [
                'arco_movimiento' => ['Arco de movimiento articular', 4],
            ],
            'Examen de retracciones' => [
                'examen_retracciones' => ['Examen de retracciones', 3],
            ],
            'Marcha' => [
                'marcha_apoyo' => ['Fase de apoyo', 3],
                'marcha_balanceo' => ['Fase de balanceo', 3],
                'equilibrio' => ['Equilibrio', 3],
            ],
            'Diagnóstico fisioterapéutico' => [
                'diagnostico_fisioterapeutico' => ['Diagnóstico fisioterapéutico', 3],
            ],
            'Conducta' => [
                'conducta' => ['Conducta', 4],
            ],
        ];
    }

    // Nombre legible que usa la bitácora en lugar del nombre de la clase
    protected static function etiquetaModelo(): string
    {
        return 'Registro de evaluación';
    }

    public function etiquetaBitacora(): string
    {
        $paciente = $this->paciente;
        $nombre = $paciente ? "{$paciente->nombres} {$paciente->apellidos}" : "paciente #{$this->paciente_id}";

        return "{$nombre} ({$this->fecha?->format('d/m/Y')})";
    }
}