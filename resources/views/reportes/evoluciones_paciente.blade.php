<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Evoluciones — {{ $paciente->nombres }} {{ $paciente->apellidos }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        h1 { font-size: 16px; margin: 0 0 2px 0; }
        .sub { color: #6b7280; margin-bottom: 14px; }
        .ficha { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .ficha td { padding: 4px 6px; border-bottom: 1px solid #e5e7eb; }
        .ficha .etq { color: #6b7280; width: 18%; }
        .sesion { border: 1px solid #d1d5db; margin-bottom: 10px; page-break-inside: avoid; }
        .sesion .cab { background: #0E9CAE; color: #ffffff; padding: 5px 8px; font-weight: bold; }
        .sesion .cuerpo { padding: 6px 8px; }
        .etiqueta { color: #6b7280; font-size: 10px; margin-top: 6px; }
        .pie { margin-top: 18px; color: #9ca3af; font-size: 9px; }
    </style>
</head>
<body>
    <h1>Clínica FIMOVE — Historial de evoluciones fisioterapéuticas</h1>
    <div class="sub">
        @if ($fechaInicio || $fechaFin)
            Periodo:
            {{ $fechaInicio ? \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') : 'inicio' }}
            al
            {{ $fechaFin ? \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') : 'hoy' }}
        @else
            Todas las sesiones registradas
        @endif
    </div>

    <table class="ficha">
        <tr>
            <td class="etq">Paciente</td>
            <td>{{ $paciente->nombres }} {{ $paciente->apellidos }}</td>
            <td class="etq">Edad</td>
            <td>{{ $paciente->fecha_nacimiento ? $paciente->fecha_nacimiento->age . ' años' : '—' }}</td>
        </tr>
        <tr>
            <td class="etq">Teléfono</td>
            <td>{{ $paciente->telefono ?? '—' }}</td>
            <td class="etq">Sesiones</td>
            <td>{{ $sesiones->count() }}</td>
        </tr>
    </table>

    @forelse ($sesiones as $sesion)
        <div class="sesion">
            <div class="cab">
                Sesión {{ $sesion->sesion }} — {{ \Carbon\Carbon::parse($sesion->fecha_registro)->format('d/m/Y') }}
            </div>
            <div class="cuerpo">
                <div class="etiqueta">Fisioterapeuta</div>
                <div>{{ $sesion->fisioterapeuta ? $sesion->fisioterapeuta->name . ' ' . $sesion->fisioterapeuta->apellido : 'Sin asignar' }}</div>

                <div class="etiqueta">Tratamiento y actividades realizadas</div>
                <div>{!! nl2br(e($sesion->descripcion)) !!}</div>

                @if ($sesion->evolucion)
                    <div class="etiqueta">Evolución</div>
                    <div>{!! nl2br(e($sesion->evolucion)) !!}</div>
                @endif
            </div>
        </div>
    @empty
        <p>No hay sesiones registradas para este paciente en el periodo indicado.</p>
    @endforelse

    <div class="pie">
        Generado por {{ $generadoPor->name }} {{ $generadoPor->apellido }}
        el {{ now()->translatedFormat('d \d\e F \d\e Y, g:i a') }}
    </div>
</body>
</html>