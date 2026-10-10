<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Tratamiento;
use Illuminate\Http\Request;

class TratamientoController extends Controller
{
    public function index(Request $request)
    {
        $query = Tratamiento::with('paciente')->orderByDesc('fecha_registro');

        if ($request->filled('paciente_id')) {
            $query->where('paciente_id', $request->paciente_id);
        }

        $tratamientos = $query->paginate(15)->withQueryString();

        $pacienteSeleccionado = $request->filled('paciente_id')
            ? Paciente::find($request->paciente_id)
            : null;

        return view('tratamientos.index', compact('tratamientos', 'pacienteSeleccionado'));
    }

    public function create(Request $request)
    {
        $paciente = Paciente::with('expedienteClinico')->findOrFail($request->paciente_id);

        if ($paciente->estadoTratamiento() === 'Alta') {
            return redirect()
                ->route('pacientes.show', $paciente)
                ->with('status', 'El paciente está dado de alta. Reactívelo para registrar nuevas sesiones.');
        }

        $siguienteSesion = Tratamiento::where('paciente_id', $paciente->id)->count() + 1;

        return view('tratamientos.create', compact('paciente', 'siguienteSesion'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'sesion' => ['required', 'integer', 'min:1'],
            'fecha_registro' => ['required', 'date'],
            'descripcion' => ['required', 'string'],
            'evolucion' => ['nullable', 'string'],
        ]);

        $paciente = Paciente::findOrFail($validated['paciente_id']);

        if ($paciente->estadoTratamiento() === 'Alta') {
            return redirect()
                ->route('pacientes.show', $paciente)
                ->with('status', 'El paciente está dado de alta. Reactívelo para registrar nuevas sesiones.');
        }

        $validated['user_id'] = auth()->id();

        Tratamiento::create($validated);

        return redirect()
            ->route('pacientes.show', $request->paciente_id)
            ->with('status', 'Sesión de tratamiento registrada correctamente.');
    }

    public function show(Tratamiento $tratamiento)
    {
        $tratamiento->load('paciente.expedienteClinico');

        return view('tratamientos.show', compact('tratamiento'));
    }

    public function edit(Tratamiento $tratamiento)
    {
        $tratamiento->load('paciente.expedienteClinico');

        return view('tratamientos.edit', compact('tratamiento'));
    }

    public function update(Request $request, Tratamiento $tratamiento)
    {
        $validated = $request->validate([
            'fecha_registro' => ['required', 'date'],
            'descripcion' => ['required', 'string'],
            'evolucion' => ['nullable', 'string'],
        ]);

        $tratamiento->update($validated);

        return redirect()
            ->route('pacientes.show', $tratamiento->paciente_id)
            ->with('status', 'Sesión de tratamiento actualizada correctamente.');
    }

    public function destroy(Tratamiento $tratamiento)
    {
        $pacienteId = $tratamiento->paciente_id;
        $tratamiento->delete();

        return redirect()
            ->route('pacientes.show', $pacienteId)
            ->with('status', 'Sesión de tratamiento eliminada correctamente.');
    }
}