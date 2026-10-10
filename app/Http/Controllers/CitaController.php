<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    /**
     * Muestra la agenda de citas de un día específico (por defecto, hoy).
     */
    public function index(Request $request)
    {
        $fecha = $request->query('fecha', now()->toDateString());

        $citas = Cita::with('paciente')
            ->whereDate('fecha', $fecha)
            ->orderBy('hora')
            ->get();

        return view('citas.index', compact('citas', 'fecha'));
    }

    /**
     * Muestra el formulario para agendar una nueva cita.
     */
    public function create(Request $request)
    {
        $pacientes = Paciente::orderBy('nombres')->get();
        $fecha = $request->query('fecha', now()->toDateString());

        return view('citas.create', compact('pacientes', 'fecha'));
    }

    /**
     * Guarda una nueva cita.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'fecha' => ['required', 'date'],
            'hora' => ['required'],
            'motivo' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', 'in:pendiente,confirmada,cancelada,atendida'],
        ]);

        $ocupada = $datos['estado'] !== 'cancelada'
            && Cita::where('fecha', $datos['fecha'])
                ->where('hora', $datos['hora'])
                ->where('estado', '!=', 'cancelada')
                ->exists();

        if ($ocupada) {
            return back()->withInput()->withErrors([
                'hora' => 'Ya hay una cita agendada para esa fecha y hora. Elija otro horario.',
            ]);
        }

        $datos['user_id'] = auth()->id();

        Cita::create($datos);

        return redirect()->route('citas.index', ['fecha' => $datos['fecha']])
            ->with('exito', 'Cita agendada correctamente.');
    }

    /**
     * (No se usa: en este sistema no hay una pantalla de detalle de cita,
     * se gestiona directo desde la agenda)
     */
    public function show(Cita $cita)
    {
        return redirect()->route('citas.edit', $cita);
    }

    /**
     * Muestra el formulario para editar una cita (cambiar estado, reprogramar).
     */
    public function edit(Cita $cita)
    {
        $pacientes = Paciente::orderBy('nombres')->get();

        return view('citas.edit', compact('cita', 'pacientes'));
    }

    /**
     * Actualiza una cita existente.
     */
    public function update(Request $request, Cita $cita)
    {
        $datos = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'fecha' => ['required', 'date'],
            'hora' => ['required'],
            'motivo' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', 'in:pendiente,confirmada,cancelada,atendida'],
        ]);

        $ocupada = $datos['estado'] !== 'cancelada'
            && Cita::where('fecha', $datos['fecha'])
                ->where('hora', $datos['hora'])
                ->where('estado', '!=', 'cancelada')
                ->where('id', '!=', $cita->id)
                ->exists();

        if ($ocupada) {
            return back()->withInput()->withErrors([
                'hora' => 'Ya hay una cita agendada para esa fecha y hora. Elija otro horario.',
            ]);
        }

        $cita->update($datos);

        return redirect()->route('citas.index', ['fecha' => $datos['fecha']])
            ->with('exito', 'Cita actualizada correctamente.');
    }

    /**
     * Cancela/elimina una cita.
     */
    public function destroy(Cita $cita)
    {
        $fecha = $cita->fecha->toDateString();
        $cita->delete();

        return redirect()->route('citas.index', ['fecha' => $fecha])
            ->with('exito', 'Cita eliminada correctamente.');
    }
}