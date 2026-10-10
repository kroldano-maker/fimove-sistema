@php
    $secciones = \App\Models\EvaluacionFisioterapeutica::secciones();
     $clase = 'w-full rounded-md border-gray-300 shadow-sm text-base px-3 py-2.5 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]';
@endphp

@include('evaluaciones._datos_paciente')

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-sm font-semibold text-gray-800 mb-4">Datos de la evaluación</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
            <label for="fecha" class="block text-sm text-gray-600 mb-1">Fecha <span class="text-red-500">*</span></label>
            <input type="date" id="fecha" name="fecha" max="{{ now()->toDateString() }}"
                   value="{{ old('fecha', $evaluacion->fecha?->format('Y-m-d')) }}" class="{{ $clase }}">
        </div>
        <div>
            <label for="hora" class="block text-sm text-gray-600 mb-1">Hora</label>
            <input type="time" id="hora" name="hora"
                   value="{{ old('hora', substr($evaluacion->hora ?? '', 0, 5)) }}" class="{{ $clase }}">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Evaluador</label>
            <p class="text-sm text-gray-800 px-3 py-2">{{ $evaluacion->evaluador->name ?? auth()->user()->name }}</p>
        </div>
    </div>
</div>

@foreach ($secciones as $titulo => $campos)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-sm font-semibold text-gray-800 mb-4">{{ $titulo }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach ($campos as $campo => $def)
                <div class="{{ count($campos) === 1 ? 'md:col-span-2' : '' }}">
                    <label for="{{ $campo }}" class="{{ count($campos) === 1 ? 'sr-only' : 'block text-sm text-gray-600 mb-1' }}">{{ $def[0] }}</label>
                    @if ($def[1] === 0)
                        <input type="text" id="{{ $campo }}" name="{{ $campo }}" maxlength="150"
                               value="{{ old($campo, $evaluacion->$campo) }}" class="{{ $clase }}">
                    @else
                        <textarea id="{{ $campo }}" name="{{ $campo }}" rows="{{ $def[1] }}"
                                  class="{{ $clase }}">{{ old($campo, $evaluacion->$campo) }}</textarea>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endforeach