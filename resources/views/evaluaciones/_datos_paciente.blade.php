@php
    $generos = ['M' => 'Masculino', 'F' => 'Femenino'];
    $datos = [
        'Nombre' => "{$paciente->nombres} {$paciente->apellidos}",
        'Edad' => $paciente->fecha_nacimiento->age . ' años',
        'Fecha de nacimiento' => $paciente->fecha_nacimiento->format('d/m/Y'),
        'Género' => $generos[$paciente->genero] ?? '—',
        'Teléfono' => $paciente->telefono ?: '—',
        'Estado civil' => $paciente->estado_civil ? ucfirst($paciente->estado_civil) : '—',
        'Profesión' => $paciente->profesion ?: '—',
        'Ocupación' => $paciente->ocupacion ?: '—',
        'Lateralidad' => $paciente->lateralidad ? ucfirst($paciente->lateralidad) : '—',
        'Dirección' => $paciente->direccion ?: '—',
    ];
@endphp

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-800">Datos del paciente</h2>
        @if (in_array(auth()->user()->rol, ['personal_administrativo', 'administrador']))
            <a href="{{ route('pacientes.edit', $paciente) }}" class="text-xs text-[#0E9CAE] hover:underline">Editar datos del paciente</a>
        @endif
    </div>
    <dl class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        @foreach ($datos as $etiqueta => $valor)
            <div class="{{ in_array($etiqueta, ['Nombre', 'Dirección']) ? 'md:col-span-2' : '' }}">
                <dt class="text-xs text-gray-500">{{ $etiqueta }}</dt>
                <dd class="text-gray-800">{{ $valor }}</dd>
            </div>
        @endforeach
    </dl>
</div>