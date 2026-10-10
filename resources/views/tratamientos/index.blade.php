<x-fimove-layout>
    <div class="max-w-5xl mx-auto py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-semibold text-gray-800">
                Tratamientos
                @if($pacienteSeleccionado)
                    <span class="text-gray-500 font-normal">— {{ $pacienteSeleccionado->nombres }} {{ $pacienteSeleccionado->apellidos }}</span>
                @endif
            </h1>

            @if($pacienteSeleccionado)
                <a href="{{ route('pacientes.show', $pacienteSeleccionado) }}" class="text-sm text-blue-600 hover:underline">
                    &larr; Volver a la ficha del paciente
                </a>
            @endif
        </div>

        @if(session('status'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 px-4 py-2 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#0E9CAE]">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Paciente</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Sesión</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Fecha</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Observaciones</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tratamientos as $tratamiento)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2 text-sm text-gray-800">
                                <a href="{{ route('pacientes.show', $tratamiento->paciente_id) }}" class="text-[#0E9CAE] hover:underline">
                                    {{ $tratamiento->paciente->nombres }} {{ $tratamiento->paciente->apellidos }}
                                </a>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">Sesión {{ $tratamiento->sesion }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($tratamiento->fecha_registro)->translatedFormat('d \d\e F, Y') }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">{{ Str::limit($tratamiento->descripcion, 60) }}</td>
                            <td class="px-4 py-2 text-right text-sm">
                                @if(in_array(auth()->user()->rol, ['fisioterapeuta', 'administrador']))
                                    <a href="{{ route('tratamientos.edit', $tratamiento) }}" class="text-[#0E9CAE] hover:underline">Editar</a>    
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                No hay sesiones de tratamiento registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tratamientos->links() }}
        </div>
    </div>
</x-fimove-layout>