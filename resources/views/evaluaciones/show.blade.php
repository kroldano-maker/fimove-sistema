<x-fimove-layout title="Evaluación fisioterapéutica — Clínica FIMOVE">
    <div class="max-w-4xl">
        

        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Evaluación fisioterapéutica</h1>
                <p class="text-sm text-gray-500">
                    {{ $evaluacion->fecha->format('d/m/Y') }}@if ($evaluacion->hora), {{ substr($evaluacion->hora, 0, 5) }}@endif
                    — Evaluó: {{ $evaluacion->evaluador->name ?? 'Sin asignar' }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('evaluaciones.pdf', $evaluacion) }}"
                   class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">Descargar PDF</a>
                @if (in_array(auth()->user()->rol, ['fisioterapeuta', 'administrador']))
                    <a href="{{ route('evaluaciones.edit', $evaluacion) }}"
                       class="border border-[#0E9CAE] text-[#0E9CAE] text-sm font-medium px-4 py-2 rounded-md hover:bg-teal-50">Editar</a>
                    <div x-data="{ abierto: false }">
                        <button type="button" @click="abierto = true"
                                class="border border-red-300 text-red-600 text-sm font-medium px-4 py-2 rounded-md hover:bg-red-50">Eliminar</button>

                        <div x-show="abierto" x-cloak x-transition.opacity
                             @keydown.escape.window="abierto = false" @click.self="abierto = false"
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Eliminar evaluación</h3>
                                <p class="text-sm text-gray-600 mb-6">
                                    Se eliminará la evaluación del {{ $evaluacion->fecha->format('d/m/Y') }}
                                    de {{ $paciente->nombres }} {{ $paciente->apellidos }}. Esta acción no se puede deshacer.
                                </p>
                                <div class="flex items-center justify-end gap-3">
                                    <button type="button" @click="abierto = false"
                                            class="text-sm text-gray-600 px-4 py-2 hover:text-gray-800">Cancelar</button>
                                    <form method="POST" action="{{ route('evaluaciones.destroy', $evaluacion) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-red-700">Sí, eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @include('evaluaciones._datos_paciente')

        @foreach ($secciones as $titulo => $campos)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-sm font-semibold text-gray-800 mb-4">{{ $titulo }}</h2>
                <dl class="space-y-3 text-sm">
                    @foreach ($campos as $campo => $def)
                        <div>
                            @if (count($campos) > 1)
                                <dt class="text-xs text-gray-500">{{ $def[0] }}</dt>
                            @endif
                            <dd class="text-gray-800">{!! $evaluacion->$campo ? nl2br(e($evaluacion->$campo)) : '<span class="text-gray-400">—</span>' !!}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        @endforeach

        <a href="{{ route('pacientes.show', $paciente) }}" class="text-sm text-gray-600 hover:text-gray-800 pb-8 inline-block">← Volver a la ficha del paciente</a>
    </div>
</x-fimove-layout>