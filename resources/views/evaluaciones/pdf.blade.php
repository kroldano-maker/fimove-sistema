<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Evaluación fisioterapéutica</title>
    <style>
        @page { margin: 28px 34px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 18px; text-align: center; margin: 0 0 2px 0; }
        .clinica { text-align: center; color: #6b7280; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        .datos td { border: 1px solid #d1d5db; padding: 4px 6px; }
        .datos .e { background: #f3f4f6; color: #374151; font-weight: bold; width: 17%; }
        .sec { margin-top: 8px; page-break-inside: avoid; }
        .sec .t { background: #0E9CAE; color: #ffffff; font-weight: bold; padding: 3px 6px; }
        .sec .c { border: 1px solid #d1d5db; border-top: none; padding: 4px 6px; }
        .sub { color: #6b7280; font-size: 10.5px; margin-top: 4px; }
        .firma { margin-top: 36px; }
        .firma td { padding-top: 6px; }
    </style>
</head>
<body>
@php
    $m = fn ($valor, $opcion) => $valor === $opcion ? '(X)' : '( )';
@endphp

<h1>EVALUACIÓN FISIOTERAPÉUTICA</h1>
<div class="clinica">Clínica FIMOVE</div>

<table class="datos">
    <tr>
        <td class="e">Fecha</td><td>{{ $evaluacion->fecha->format('d/m/Y') }}</td>
        <td class="e">Hora</td><td>{{ $evaluacion->hora ? substr($evaluacion->hora, 0, 5) : '' }}</td>
    </tr>
    <tr>
        <td class="e">Nombre</td><td colspan="3">{{ $paciente->nombres }} {{ $paciente->apellidos }}</td>
    </tr>
    <tr>
        <td class="e">Dirección</td><td colspan="3">{{ $paciente->direccion }}</td>
    </tr>
    <tr>
        <td class="e">Género</td><td>M {{ $m($paciente->genero, 'M') }} &nbsp; F {{ $m($paciente->genero, 'F') }}</td>
        <td class="e">Fecha de nacimiento</td><td>{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td class="e">Edad</td><td>{{ $paciente->fecha_nacimiento->age }} años</td>
        <td class="e">Teléfono</td><td>{{ $paciente->telefono }}</td>
    </tr>
    <tr>
        <td class="e">Estado civil</td>
        <td colspan="3">
            Casado {{ $m($paciente->estado_civil, 'casado') }} &nbsp;
            Soltero {{ $m($paciente->estado_civil, 'soltero') }} &nbsp;
            Unido {{ $m($paciente->estado_civil, 'unido') }} &nbsp;
            Viudo {{ $m($paciente->estado_civil, 'viudo') }} &nbsp;
            Divorciado {{ $m($paciente->estado_civil, 'divorciado') }}
        </td>
    </tr>
    <tr>
        <td class="e">Profesión</td><td>{{ $paciente->profesion }}</td>
        <td class="e">Ocupación</td><td>{{ $paciente->ocupacion }}</td>
    </tr>
    <tr>
        <td class="e">Lateralidad</td>
        <td colspan="3">
            Derecho {{ $m($paciente->lateralidad, 'derecho') }} &nbsp;
            Izquierdo {{ $m($paciente->lateralidad, 'izquierdo') }}
        </td>
    </tr>
</table>

@foreach ($secciones as $titulo => $campos)
    <div class="sec">
        <div class="t">{{ mb_strtoupper($titulo) }}</div>
        <div class="c">
            @foreach ($campos as $campo => $def)
                @if (count($campos) > 1)
                    <div class="sub">{{ $def[0] }}</div>
                @endif
                <div>{!! $evaluacion->$campo ? nl2br(e($evaluacion->$campo)) : '—' !!}</div>
            @endforeach
        </div>
    </div>
@endforeach

<table class="firma">
    <tr>
        <td style="width:55%">Nombre del evaluador: <strong>{{ $evaluacion->evaluador->name ?? 'Sin asignar' }}</strong></td>
        <td>Firma y sello: ______________________________</td>
    </tr>
</table>
</body>
</html>