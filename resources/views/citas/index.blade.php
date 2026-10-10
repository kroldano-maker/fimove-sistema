@php
    $coloresEstado = [
        'pendiente' => 'bg-yellow-50 text-yellow-700',
        'confirmada' => 'bg-green-50 text-green-700',
        'cancelada' => 'bg-red-50 text-red-700',
        'atendida' => 'bg-gray-100 text-gray-600',
    ];
    $fechaCarbon = \Carbon\Carbon::parse($fecha);
@endphp
<x-fimove-layout title="Agenda — Clínica FIMOVE">
    <div class="max-w-5xl mx-auto py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-semibold text-gray-800">
                Agenda — {{ $fechaCarbon->translatedFormat('l d \d\e F') }}
            </h1>
            @if (in_array(auth()->user()->rol, ['personal_administrativo', 'administrador']))
                <a href="{{ route('citas.create', ['fecha' => $fecha]) }}"
                   class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                    + Agendar cita
                </a>
            @endif
        </div>

        <div class="flex items-center gap-4 mb-4 text-sm">
            <a href="{{ route('citas.index', ['fecha' => $fechaCarbon->copy()->subDay()->toDateString()]) }}"
               class="text-gray-500 hover:text-[#0E9CAE]">&larr; Día anterior</a>
            <a href="{{ route('citas.index', ['fecha' => now()->toDateString()]) }}"
               class="text-[#0E9CAE] font-medium hover:underline">Hoy</a>
            <a href="{{ route('citas.index', ['fecha' => $fechaCarbon->copy()->addDay()->toDateString()]) }}"
               class="text-gray-500 hover:text-[#0E9CAE]">Día siguiente &rarr;</a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#0E9CAE]">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Hora</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Paciente</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Estado</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($citas as $cita)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-800">{{ \Carbon\Carbon::parse($cita->hora)->format('g:i a') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="text-xs px-2 py-1 rounded-full {{ $coloresEstado[$cita->estado] ?? '' }}">
                                    {{ ucfirst($cita->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                @if (in_array(auth()->user()->rol, ['personal_administrativo', 'administrador']))
                                    <a href="{{ route('citas.edit', $cita) }}" class="text-[#0E9CAE] hover:underline font-medium">Editar</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                No hay citas agendadas para este día.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-fimove-layout>