<x-fimove-layout title="Reportes — Clínica FIMOVE">
    <div class="max-w-4xl mx-auto py-6">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Generar reporte</h1>

        <form method="GET" action="{{ route('reportes.index') }}" class="flex flex-wrap items-end gap-3 mb-6">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Reporte clínico</label>
                <select name="tipo" class="rounded-md border-gray-300 shadow-sm text-sm">
                    @foreach($tipos as $clave => $etiqueta)
                        <option value="{{ $clave }}" @selected($tipo === $clave)>{{ $etiqueta }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Desde</label>
                <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}"
                       class="rounded-md border-gray-300 shadow-sm text-sm">
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Hasta</label>
                <input type="date" name="fecha_fin" value="{{ $fechaFin }}"
                       class="rounded-md border-gray-300 shadow-sm text-sm">
            </div>

            <button type="submit" class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                Filtrar
            </button>
        </form>

        <div class="bg-white rounded-lg shadow overflow-hidden mb-4">
            <table class="min-w-full divide-y divide-gray-200">
                @switch($tipo)
                    @case('pacientes')
                        <thead class="bg-[#0E9CAE]">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Paciente</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Total de sesiones</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Última sesión</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($resultados as $fila)
                                <tr>
                                    <td class="px-4 py-2 text-sm">{{ $fila['paciente']->nombres }} {{ $fila['paciente']->apellidos }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $fila['total_sesiones'] }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $fila['ultima_sesion'] ? \Carbon\Carbon::parse($fila['ultima_sesion'])->format('d/m/Y') : '—' }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-700' => $fila['estado'] === 'Activo',
                                            'bg-blue-100 text-blue-700' => $fila['estado'] === 'Alta',
                                            'bg-red-100 text-red-700' => $fila['estado'] === 'Abandonado',
                                            'bg-gray-100 text-gray-600' => $fila['estado'] === 'Sin tratamiento iniciado',
                                        ])>
                                            {{ $fila['estado'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Sin resultados.</td></tr>
                            @endforelse
                        </tbody>
                        @break

                    @case('citas_estado')
                        <thead class="bg-[#0E9CAE]">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Estado</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($resultados as $fila)
                                <tr>
                                    <td class="px-4 py-2 text-sm capitalize">{{ $fila->estado }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $fila->total }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="px-4 py-6 text-center text-sm text-gray-500">Sin resultados.</td></tr>
                            @endforelse
                        </tbody>
                        @break

                    @case('tratamientos_paciente')
                        <thead class="bg-[#0E9CAE]">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Paciente</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Sesiones completadas</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Avance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($resultados as $fila)
                                <tr>
                                    <td class="px-4 py-2 text-sm">{{ $fila['paciente']->nombres }} {{ $fila['paciente']->apellidos }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $fila['total_sesiones'] }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $fila['ultima_sesion']->evolucion ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">Sin resultados.</td></tr>
                            @endforelse
                        </tbody>
                        @break

                    @case('tratamientos_fisioterapeuta')
                        <thead class="bg-[#0E9CAE]">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Fisioterapeuta</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Sesiones registradas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($resultados as $fila)
                                <tr>
                                    <td class="px-4 py-2 text-sm">
                                        {{ $fila['fisioterapeuta'] ? $fila['fisioterapeuta']->name . ' ' . $fila['fisioterapeuta']->apellido : 'Sin asignar' }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $fila['total_sesiones'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="px-4 py-6 text-center text-sm text-gray-500">Sin resultados.</td></tr>
                            @endforelse
                        </tbody>
                        @break

                    @case('asistencia_citas')
                        <thead class="bg-[#0E9CAE]">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Estado</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Cantidad</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($resultados as $fila)
                                <tr>
                                    <td class="px-4 py-2 text-sm capitalize">{{ $fila['estado'] }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $fila['cantidad'] }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $fila['porcentaje'] }}%</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">Sin resultados.</td></tr>
                            @endforelse
                        </tbody>
                        @break
                @endswitch
            </table>
        </div>

        <a href="{{ route('reportes.descargar', request()->query()) }}"
           class="inline-block bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
        Descargar reporte
        </a>

        <div class="bg-white rounded-lg shadow p-6 mt-10">
            <h2 class="text-base font-semibold text-gray-800 mb-1">Historial de evoluciones de un paciente</h2>
            <p class="text-sm text-gray-500 mb-4">Descarga en un solo PDF las sesiones de un paciente, ordenadas por fecha de atención.</p>

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="GET" action="{{ route('reportes.evoluciones') }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Paciente</label>
                    <select name="paciente_id" class="rounded-md border-gray-300 shadow-sm text-sm min-w-[16rem]">
                        <option value="">Seleccione un paciente</option>
                        @foreach ($pacientes as $p)
                            <option value="{{ $p->id }}" @selected(old('paciente_id') == $p->id)>{{ $p->apellidos }}, {{ $p->nombres }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Desde</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                           class="rounded-md border-gray-300 shadow-sm text-sm">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Hasta</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                           class="rounded-md border-gray-300 shadow-sm text-sm">
                </div>

                <button type="submit" class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                    Descargar historial
                </button>
            </form>
        </div>
    </div>
</x-fimove-layout>