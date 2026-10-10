<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $tipos[$tipo] }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 18px; margin-bottom: 2px; }
        .subtitulo { color: #6b7280; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { text-align: left; padding: 6px 8px; border-bottom: 1px solid #e5e7eb; }
        th { background-color: #f9fafb; text-transform: uppercase; font-size: 10px; color: #6b7280; }
        .pie { margin-top: 24px; font-size: 10px; color: #9ca3af; }
        .capitalize { text-transform: capitalize; }
    </style>
</head>
<body>
    <h1>Clínica FIMOVE — {{ $tipos[$tipo] }}</h1>
    <p class="subtitulo">
        @if($fechaInicio || $fechaFin)
            Período: {{ $fechaInicio ?: 'inicio' }} — {{ $fechaFin ?: 'hoy' }}
        @else
            Todos los registros
        @endif
    </p>

    <table>
        @switch($tipo)
            @case('pacientes')
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Total de sesiones</th>
                        <th>Última sesión</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $fila)
                        <tr>
                            <td>{{ $fila['paciente']->nombres }} {{ $fila['paciente']->apellidos }}</td>
                            <td>{{ $fila['total_sesiones'] }}</td>
                            <td>{{ $fila['ultima_sesion'] ? \Carbon\Carbon::parse($fila['ultima_sesion'])->format('d/m/Y') : '—' }}</td>
                            <td>{{ $fila['estado'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Sin resultados.</td></tr>
                    @endforelse
                </tbody>
                @break   

            @case('citas_estado')
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $fila)
                        <tr>
                            <td class="capitalize">{{ $fila->estado }}</td>
                            <td>{{ $fila->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2">Sin resultados.</td></tr>
                    @endforelse
                </tbody>
                @break

            @case('tratamientos_paciente')
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Sesiones completadas</th>
                        <th>Última sesión</th>
                        <th>Avance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $fila)
                        <tr>
                            <td>{{ $fila['paciente']->nombres }} {{ $fila['paciente']->apellidos }}</td>
                            <td>{{ $fila['total_sesiones'] }}</td>
                            <td>{{ optional($fila['ultima_sesion']->fecha_registro)->format('d/m/Y') }}</td>
                            <td>{{ $fila['ultima_sesion']->evolucion ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Sin resultados.</td></tr>
                    @endforelse
                </tbody>
                @break

            @case('tratamientos_fisioterapeuta')
                <thead>
                    <tr>
                        <th>Fisioterapeuta</th>
                        <th>Sesiones registradas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $fila)
                        <tr>
                            <td>{{ $fila['fisioterapeuta'] ? $fila['fisioterapeuta']->name . ' ' . $fila['fisioterapeuta']->apellido : 'Sin asignar' }}</td>
                            <td>{{ $fila['total_sesiones'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2">Sin resultados.</td></tr>
                    @endforelse
                </tbody>
                @break

            @case('asistencia_citas')
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $fila)
                        <tr>
                            <td class="capitalize">{{ $fila['estado'] }}</td>
                            <td>{{ $fila['cantidad'] }}</td>
                            <td>{{ $fila['porcentaje'] }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Sin resultados.</td></tr>
                    @endforelse
                </tbody>
                @break
        @endswitch
    </table>

    <p class="pie">
        Generado por {{ $generadoPor->name }} {{ $generadoPor->apellido }}
        el {{ now()->translatedFormat('d \d\e F \d\e Y, g:i a') }}
    </p>
</body>
</html>