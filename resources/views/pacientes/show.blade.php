<x-fimove-layout title="{{ $paciente->nombres }} {{ $paciente->apellidos }} — Clínica FIMOVE">
    <div class="max-w-5xl mx-auto py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">{{ $paciente->nombres }} {{ $paciente->apellidos }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    @if ($paciente->fecha_nacimiento)
                        {{ $paciente->fecha_nacimiento->age }} años
                    @endif
                </p>
                @php($estado = $paciente->estadoTratamiento())
                <span @class([
                    'inline-block mt-2 text-xs font-medium px-2 py-0.5 rounded-full',
                    'bg-green-100 text-green-700' => $estado === 'Activo',
                    'bg-blue-100 text-blue-700' => $estado === 'Alta',
                    'bg-red-100 text-red-700' => $estado === 'Abandonado',
                    'bg-gray-100 text-gray-600' => $estado === 'Sin tratamiento iniciado',
                ])>
                    {{ $estado }}@if ($estado === 'Alta' && $paciente->fecha_alta) · {{ $paciente->fecha_alta->format('d/m/Y') }}@endif
                </span>
            </div>
            <div class="flex gap-2">
                @if (in_array(auth()->user()->rol, ['fisioterapeuta', 'administrador']))
                    @if ($estado === 'Alta')
                        <form method="POST" action="{{ route('pacientes.reactivar', $paciente) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="border border-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-md hover:bg-gray-50">
                                Reactivar
                            </button>
                        </form>
                    @elseif ($paciente->tratamientos->isNotEmpty())
                        <form method="POST" action="{{ route('pacientes.alta', $paciente) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                                Finalizar tratamiento
                            </button>
                        </form>
                    @endif
                @endif
                @if (in_array(auth()->user()->rol, ['personal_administrativo', 'administrador']))
                <a href="{{ route('pacientes.edit', $paciente) }}"
                   class="border border-[#0E9CAE] text-[#0E9CAE] text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0E9CAE]/10">
                    Editar
                </a>
                <form id="form-eliminar-paciente" method="POST" action="{{ route('pacientes.destroy', $paciente) }}">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="button"
                        @click="$dispatch('confirmar-eliminar', { formId: 'form-eliminar-paciente', mensaje: '¿Seguro que quiere eliminar este paciente? Esta acción no se puede deshacer.' })"
                        class="border border-red-300 text-red-600 text-sm font-medium px-4 py-2 rounded-md hover:bg-red-50">
                    Eliminar
                </button>
                @endif
            </div>
        </div>

        @if(session('status'))
            <div class="mb-6 rounded-md bg-green-50 border border-green-200 text-green-700 px-4 py-2 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase mb-1">Teléfono</p>
                    <p class="text-gray-800">{{ $paciente->telefono ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase mb-1">Correo</p>
                    <p class="text-gray-800">{{ $paciente->correo ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase mb-1">Dirección</p>
                    <p class="text-gray-800">{{ $paciente->direccion ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase mb-1">Fecha de registro</p>
                    <p class="text-gray-800">{{ optional($paciente->fecha_registro)->format('d/m/Y') ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <h2 class="font-semibold text-gray-800 px-6 py-4 border-b border-gray-100">Citas</h2>
            <div>
                @forelse ($paciente->citas as $cita)
                    <div class="px-6 py-3 border-b border-gray-100 text-sm flex justify-between last:border-b-0">
                        <span class="text-gray-800">{{ \Carbon\Carbon::parse($cita->fecha)->translatedFormat('d M Y') }}, {{ \Carbon\Carbon::parse($cita->hora)->format('g:i a') }}</span>
                        <span class="text-gray-500 capitalize">{{ $cita->estado }}</span>
                    </div>
                @empty
                    <p class="px-6 py-4 text-sm text-gray-500">Sin citas registradas.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow mb-6">
            <div class="flex items-center justify-between px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-800">Evaluaciones fisioterapéuticas</h2>
                @if (in_array(auth()->user()->rol, ['fisioterapeuta', 'administrador']))
                    <a href="{{ route('evaluaciones.create', $paciente) }}"
                   class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">+ Nueva evaluación</a>
                @endif
            </div>
            @forelse ($paciente->evaluaciones as $evaluacion)
            <div class="flex items-center justify-between px-6 py-3 border-t border-gray-100 text-sm">
                <div>
                    <p class="text-gray-800">Evaluación — {{ $evaluacion->fecha->format('d/m/Y') }}</p>
                    <p class="text-xs text-gray-500">Evaluó: {{ $evaluacion->evaluador->name ?? 'Sin asignar' }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('evaluaciones.show', $evaluacion) }}" class="text-[#0E9CAE] hover:underline">Ver</a>
                    <a href="{{ route('evaluaciones.pdf', $evaluacion) }}" class="text-[#0E9CAE] hover:underline">PDF</a>
                </div>
            </div>
            @empty
                <p class="px-6 py-4 border-t border-gray-100 text-sm text-gray-500">Aún no hay evaluaciones registradas.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Tratamientos</h2>
                <div class="flex items-center gap-2">
                    @if($paciente->tratamientos->isNotEmpty())
                        <a href="{{ route('reportes.evoluciones', ['paciente_id' => $paciente->id]) }}"
                           class="border border-[#0E9CAE] text-[#0E9CAE] text-sm font-medium px-3 py-1.5 rounded-md hover:bg-[#0E9CAE]/10">
                            Descargar evoluciones
                        </a>
                    @endif
                    @if(in_array(auth()->user()->rol, ['fisioterapeuta', 'administrador']) && $estado !== 'Alta')
                        <a href="{{ route('tratamientos.create', ['paciente_id' => $paciente->id]) }}"
                           class="bg-[#0E9CAE] text-white text-sm font-medium px-3 py-1.5 rounded-md hover:bg-[#0C8798]">
                            + Registrar sesión
                        </a>
                    @endif
                </div>
            </div>
            <div>
                @forelse ($paciente->tratamientos->sortByDesc('fecha_registro') as $tratamiento)
                    <div class="px-6 py-3 border-b border-gray-100 text-sm flex justify-between items-start gap-4 last:border-b-0">
                        <div>
                            <p class="font-medium text-gray-700">
                                Sesión {{ $tratamiento->sesion }}
                                — {{ \Carbon\Carbon::parse($tratamiento->fecha_registro)->translatedFormat('d M Y') }}
                            </p>
                            <p class="text-gray-500">{{ $tratamiento->descripcion ?? 'Sesión registrada' }}</p>
                        </div>
                        @if(in_array(auth()->user()->rol, ['fisioterapeuta', 'administrador']))
                            <a href="{{ route('tratamientos.edit', $tratamiento) }}" class="text-[#0E9CAE] hover:underline font-medium shrink-0">
                                Editar
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="px-6 py-4 text-sm text-gray-500">Sin tratamientos registrados todavía.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-fimove-layout>