<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Tratamiento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReporteController extends Controller
{
        protected array $tipos = [
        'pacientes' => 'Pacientes por estado de tratamiento',
        'citas_estado' => 'Citas por estado',
        'tratamientos_paciente' => 'Sesiones de tratamiento por paciente',
        'tratamientos_fisioterapeuta' => 'Sesiones de tratamiento por fisioterapeuta',
        'asistencia_citas' => 'Resumen de asistencia a citas',
    ];

    public function index(Request $request)
    {
        $tipo = $request->input('tipo', 'pacientes');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $resultados = $this->generar($tipo, $fechaInicio, $fechaFin);

        return view('reportes.index', [
            'tipos' => $this->tipos,
            'tipo' => $tipo,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'resultados' => $resultados,
            'pacientes' => Paciente::orderBy('apellidos')->orderBy('nombres')->get(['id', 'nombres', 'apellidos']),
        ]);
    }

    public function descargar(Request $request)
    {
        $tipo = $request->input('tipo', 'pacientes');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $resultados = $this->generar($tipo, $fechaInicio, $fechaFin);

        $pdf = Pdf::loadView('reportes.pdf', [
            'tipos' => $this->tipos,
            'tipo' => $tipo,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'resultados' => $resultados,
            'generadoPor' => auth()->user(),
        ])->setPaper('letter', 'portrait');

        $nombreArchivo = 'reporte_' . $tipo . '_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    public function evolucionesPaciente(Request $request)
    {
        $datos = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ], [
            'paciente_id.required' => 'Seleccione un paciente.',
            'paciente_id.exists' => 'El paciente seleccionado no existe.',
            'fecha_fin.after_or_equal' => 'La fecha final no puede ser anterior a la inicial.',
        ]);

        $paciente = Paciente::findOrFail($datos['paciente_id']);
        $fechaInicio = $datos['fecha_inicio'] ?? null;
        $fechaFin = $datos['fecha_fin'] ?? null;

        $sesiones = Tratamiento::with('fisioterapeuta')
            ->where('paciente_id', $paciente->id)
            ->when($fechaInicio, fn ($q) => $q->whereDate('fecha_registro', '>=', $fechaInicio))
            ->when($fechaFin, fn ($q) => $q->whereDate('fecha_registro', '<=', $fechaFin))
            ->orderBy('fecha_registro')
            ->orderBy('sesion')
            ->get();

        $pdf = Pdf::loadView('reportes.evoluciones_paciente', [
            'paciente' => $paciente,
            'sesiones' => $sesiones,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'generadoPor' => auth()->user(),
        ])->setPaper('letter', 'portrait');

        $nombreArchivo = 'evoluciones_' . Str::slug($paciente->nombres . ' ' . $paciente->apellidos)
            . '_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    protected function generar(string $tipo, ?string $fechaInicio, ?string $fechaFin)
    {
        return match ($tipo) {
            'pacientes' => Paciente::query()
                ->when($fechaInicio, fn ($q) => $q->whereDate('fecha_registro', '>=', $fechaInicio))
                ->when($fechaFin, fn ($q) => $q->whereDate('fecha_registro', '<=', $fechaFin))
                ->with(['tratamientos', 'citas'])
                ->orderBy('apellidos')
                ->get()
                ->map(function ($paciente) {
                    $ultimaSesion = $paciente->tratamientos->sortByDesc('fecha_registro')->first();

                    return [
                        'paciente' => $paciente,
                        'total_sesiones' => $paciente->tratamientos->count(),
                        'ultima_sesion' => $ultimaSesion?->fecha_registro,
                        'estado' => $paciente->estadoTratamiento(),
                    ];
                }),

            'citas_estado' => Cita::query()
                ->when($fechaInicio, fn ($q) => $q->whereDate('fecha', '>=', $fechaInicio))
                ->when($fechaFin, fn ($q) => $q->whereDate('fecha', '<=', $fechaFin))
                ->select('estado', DB::raw('count(*) as total'))
                ->groupBy('estado')
                ->get(),

            'tratamientos_paciente' => Tratamiento::with('paciente')
                ->when($fechaInicio, fn ($q) => $q->whereDate('fecha_registro', '>=', $fechaInicio))
                ->when($fechaFin, fn ($q) => $q->whereDate('fecha_registro', '<=', $fechaFin))
                ->get()
                ->groupBy('paciente_id')
                ->map(function ($sesiones) {
                    return [
                        'paciente' => $sesiones->first()->paciente,
                        'total_sesiones' => $sesiones->count(),
                        'ultima_sesion' => $sesiones->sortByDesc('fecha_registro')->first(),
                    ];
                })
                ->values(),

            'tratamientos_fisioterapeuta' => Tratamiento::with('fisioterapeuta')
                ->when($fechaInicio, fn ($q) => $q->whereDate('fecha_registro', '>=', $fechaInicio))
                ->when($fechaFin, fn ($q) => $q->whereDate('fecha_registro', '<=', $fechaFin))
                ->get()
                ->groupBy('user_id')
                ->map(function ($sesiones) {
                    return [
                        'fisioterapeuta' => $sesiones->first()->fisioterapeuta,
                        'total_sesiones' => $sesiones->count(),
                    ];
                })
                ->values(),

            'asistencia_citas' => (function () use ($fechaInicio, $fechaFin) {
                $citas = Cita::query()
                    ->when($fechaInicio, fn ($q) => $q->whereDate('fecha', '>=', $fechaInicio))
                    ->when($fechaFin, fn ($q) => $q->whereDate('fecha', '<=', $fechaFin))
                    ->get();

                $total = $citas->count();

                return collect(['pendiente', 'confirmada', 'cancelada', 'atendida'])
                    ->map(function ($estado) use ($citas, $total) {
                        $cantidad = $citas->where('estado', $estado)->count();

                        return [
                            'estado' => $estado,
                            'cantidad' => $cantidad,
                            'porcentaje' => $total > 0 ? round(($cantidad / $total) * 100, 1) : 0,
                        ];
                    });
            })(),

            default => collect(),
        };
    }
}