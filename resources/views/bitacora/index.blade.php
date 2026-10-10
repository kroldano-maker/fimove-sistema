<x-fimove-layout title="Bitácora — Clínica FIMOVE">
    <div class="max-w-5xl mx-auto py-6">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Bitácora del sistema</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#0E9CAE]">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Fecha</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Usuario</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Acción</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Detalle</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($registros as $registro)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-600 whitespace-nowrap">{{ $registro->fecha->format('d/m/Y g:i a') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                @if($registro->usuario)
                                    {{ $registro->usuario->name }} {{ $registro->usuario->apellido }}
                                @else
                                    <span class="text-gray-400">{{ $registro->email ?? 'Desconocido' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $registro->accion }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600">{{ $registro->descripcion }}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">{{ $registro->ip }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-2 text-sm text-gray-500">Sin registros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $registros->links() }}</div>
    </div>
</x-fimove-layout>