<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionFisioterapeutica;
use App\Models\Paciente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EvaluacionController extends Controller
{
    private function validar(Request $request): array
    {
        $reglas = [
            'fecha' => ['required', 'date', 'before_or_equal:today'],
            'hora' => ['nullable', 'date_format:H:i'],
        ];

        foreach (EvaluacionFisioterapeutica::secciones() as $campos) {
            foreach ($campos as $campo => $def) {
                $reglas[$campo] = ['nullable', 'string', 'max:' . ($def[1] === 0 ? 150 : 3000)];
            }
        }

        return $request->validate($reglas, [
            'fecha.required' => 'La fecha de la evaluación es obligatoria.',
            'fecha.date' => 'La fecha de la evaluación no es válida.',
            'fecha.before_or_equal' => 'La fecha de la evaluación no puede ser futura.',
            'hora.date_format' => 'La hora no es válida.',
            'max' => 'El texto es demasiado largo (máximo :max caracteres).',
        ]);
    }

    public function create(Paciente $paciente)
    {
        $evaluacion = new EvaluacionFisioterapeutica([
            'fecha' => now()->toDateString(),
            'hora' => now()->format('H:i'),
        ]);

        return view('evaluaciones.create', compact('paciente', 'evaluacion'));
    }

    public function store(Request $request, Paciente $paciente)
    {
        $datos = $this->validar($request);
        $datos['paciente_id'] = $paciente->id;
        $datos['user_id'] = auth()->id();

        EvaluacionFisioterapeutica::create($datos);

        return redirect()->route('pacientes.show', $paciente)
            ->with('exito', 'Evaluación fisioterapéutica registrada correctamente.');
    }

    public function show(EvaluacionFisioterapeutica $evaluacion)
    {
        $evaluacion->load(['paciente', 'evaluador']);

        return view('evaluaciones.show', [
            'evaluacion' => $evaluacion,
            'paciente' => $evaluacion->paciente,
            'secciones' => EvaluacionFisioterapeutica::secciones(),
        ]);
    }

    public function edit(EvaluacionFisioterapeutica $evaluacion)
    {
        $evaluacion->load(['paciente', 'evaluador']);

        return view('evaluaciones.edit', [
            'evaluacion' => $evaluacion,
            'paciente' => $evaluacion->paciente,
        ]);
    }

    public function update(Request $request, EvaluacionFisioterapeutica $evaluacion)
    {
        $evaluacion->update($this->validar($request));

        return redirect()->route('evaluaciones.show', $evaluacion)
            ->with('exito', 'Evaluación actualizada correctamente.');
    }

    public function destroy(EvaluacionFisioterapeutica $evaluacion)
    {
        $paciente = $evaluacion->paciente;
        $evaluacion->delete();

        return redirect()->route('pacientes.show', $paciente)
            ->with('exito', 'Evaluación eliminada correctamente.');
    }

    public function pdf(EvaluacionFisioterapeutica $evaluacion)
    {
        $evaluacion->load(['paciente', 'evaluador']);
        $paciente = $evaluacion->paciente;

        $pdf = Pdf::loadView('evaluaciones.pdf', [
            'evaluacion' => $evaluacion,
            'paciente' => $paciente,
            'secciones' => EvaluacionFisioterapeutica::secciones(),
        ])->setPaper('letter', 'portrait');

        $nombreArchivo = 'evaluacion_' . Str::slug($paciente->nombres . ' ' . $paciente->apellidos)
            . '_' . $evaluacion->fecha->format('Y-m-d') . '.pdf';

        return $pdf->download($nombreArchivo);
    }
}