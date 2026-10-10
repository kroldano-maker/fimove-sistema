<x-fimove-layout title="Pacientes — Clínica FIMOVE">
    <div class="max-w-5xl mx-auto py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Pacientes registrados</h1>
            @if (in_array(auth()->user()->rol, ['personal_administrativo', 'administrador']))
                <a href="{{ route('pacientes.create') }}"
                   class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                    + Nuevo paciente
                </a>
            @endif
        </div>

        <form method="GET" class="mb-4">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre"
                   class="w-full max-w-sm rounded-md border-gray-300 text-sm shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]"
                   onchange="this.form.submit()">
        </form>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#0E9CAE]">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Nombre</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Teléfono</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Próxima cita</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($pacientes as $paciente)
                        @php $proximaCita = $paciente->citas->first(); @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $paciente->nombres }} {{ $paciente->apellidos }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $paciente->telefono ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if ($proximaCita)
                                    <span class="inline-block px-2 py-0.5 rounded-full bg-[#F2A900]/15 text-[#8a6200] text-xs font-medium">
                                        {{ \Carbon\Carbon::parse($proximaCita->fecha)->translatedFormat('d M') }},
                                        {{ \Carbon\Carbon::parse($proximaCita->hora)->format('g:i a') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">Sin cita</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-sm whitespace-nowrap">
                                <a href="{{ route('pacientes.show', $paciente) }}" class="text-[#0E9CAE] hover:underline font-medium">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                Todavía no hay pacientes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pacientes->links() }}
        </div>
    </div>
</x-fimove-layout>