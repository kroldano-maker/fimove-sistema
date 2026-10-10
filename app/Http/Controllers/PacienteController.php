<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    /**
     * Muestra el listado de pacientes registrados.
     */
        public function index(Request $request)
    {
        $pacientes = Paciente::query()
            ->when($request->buscar, function ($query, $buscar) {
                foreach (preg_split('/\s+/', trim($buscar)) as $palabra) {
                    $query->where(function ($q) use ($palabra) {
                        $q->where('nombres', 'like', "%{$palabra}%")
                            ->orWhere('apellidos', 'like', "%{$palabra}%");
                    });
                }
            })
            ->with(['citas' => function ($query) {
                $query->where('fecha', '>=', now()->toDateString())
                    ->orderBy('fecha')
                    ->orderBy('hora');
            }])
            ->orderBy('apellidos')
            ->paginate(10)
            ->withQueryString();

        return view('pacientes.index', compact('pacientes'));
    }

    /**
     * Muestra el formulario para registrar un nuevo paciente.
     */
    public function create()
    {
        return view('pacientes.create');
    }

    /**
     * Guarda un nuevo paciente en la base de datos.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombres' => [
                'required', 'string', 'max:100',
                Rule::unique('pacientes')->where(fn ($q) => $q
                    ->where('apellidos', $request->apellidos)
                    ->where('fecha_nacimiento', $request->fecha_nacimiento)),
            ],
            'apellidos' => ['required', 'string', 'max:100'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:150'],
            'genero' => ['nullable', 'in:M,F'],
            'estado_civil' => ['nullable', 'in:casado,soltero,unido,viudo,divorciado'],
            'profesion' => ['nullable', 'string', 'max:100'],
            'ocupacion' => ['nullable', 'string', 'max:100'],
            'lateralidad' => ['nullable', 'in:derecho,izquierdo'],
        ], [
            'nombres.unique' => 'Ya existe un paciente con el mismo nombre, apellidos y fecha de nacimiento.',
        ]);

        $datos['user_id'] = auth()->id();
        $datos['fecha_registro'] = now();

        Paciente::create($datos);

        return redirect()->route('pacientes.index')
            ->with('exito', 'Paciente registrado correctamente.');
    }

    /**
     * Muestra el detalle de un paciente (expediente, citas, tratamientos).
     */
    public function show(Paciente $paciente)
    {
        $paciente->load(['citas', 'tratamientos', 'evaluaciones.evaluador']);

        return view('pacientes.show', compact('paciente'));
    }

    /**
     * Muestra el formulario para editar un paciente existente.
     */
    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    /**
     * Actualiza los datos de un paciente existente.
     */
    public function update(Request $request, Paciente $paciente)
    {
        $datos = $request->validate([
            'nombres' => [
                'required', 'string', 'max:100',
                Rule::unique('pacientes')->ignore($paciente->id)->where(fn ($q) => $q
                    ->where('apellidos', $request->apellidos)
                    ->where('fecha_nacimiento', $request->fecha_nacimiento)),
            ],
            'apellidos' => ['required', 'string', 'max:100'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:150'],
            'genero' => ['nullable', 'in:M,F'],
            'estado_civil' => ['nullable', 'in:casado,soltero,unido,viudo,divorciado'],
            'profesion' => ['nullable', 'string', 'max:100'],
            'ocupacion' => ['nullable', 'string', 'max:100'],
            'lateralidad' => ['nullable', 'in:derecho,izquierdo'],
        ], [
            'nombres.unique' => 'Ya existe un paciente con el mismo nombre, apellidos y fecha de nacimiento.',
        ]);

        $paciente->update($datos);

        return redirect()->route('pacientes.index')
            ->with('exito', 'Paciente actualizado correctamente.');
    }

        /**
     * Elimina un paciente.
     */
    public function destroy(Paciente $paciente)
    {
        try {
            $paciente->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('pacientes.show', $paciente)
                    ->with('error', 'No se puede eliminar este paciente porque tiene citas, tratamientos o evaluaciones registradas. Elimina primero esos registros si realmente deseas continuar.');
            }

            throw $e;
        }

                return redirect()->route('pacientes.index')
            ->with('exito', 'Paciente eliminado correctamente.');
    }

    /**
     * Finaliza el tratamiento del paciente (alta).
     */
    public function darAlta(Paciente $paciente)
    {
        if ($paciente->tratamientos()->doesntExist()) {
            return redirect()->route('pacientes.show', $paciente)
                ->with('error', 'Este paciente no tiene sesiones registradas, no se puede dar de alta.');
        }

        $paciente->fecha_alta = now()->toDateString();
        $paciente->save();

        return redirect()->route('pacientes.show', $paciente)
            ->with('exito', 'Tratamiento finalizado. El paciente quedó dado de alta.');
    }

    /**
     * Revierte el alta: el paciente vuelve a evaluarse según sus sesiones y citas.
     */
    public function reactivar(Paciente $paciente)
    {
        $paciente->fecha_alta = null;
        $paciente->save();

        return redirect()->route('pacientes.show', $paciente)
            ->with('exito', 'Alta revertida. El paciente vuelve a estar en tratamiento.');
    }
}