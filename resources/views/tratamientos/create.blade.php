<x-fimove-layout>
    <div class="max-w-2xl mx-auto py-6">
        <h1 class="text-xl font-semibold text-gray-800 mb-1">Registrar sesión de tratamiento</h1>
        <p class="text-sm text-gray-500 mb-6">
            {{ $paciente->nombres }} {{ $paciente->apellidos }}
            — {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años
        </p>

        @if($paciente->expedienteClinico && $paciente->expedienteClinico->diagnostico)
            <div class="mb-6 rounded-md bg-blue-50 border border-blue-200 px-4 py-2 text-sm text-blue-800">
                <span class="font-medium">Diagnóstico:</span> {{ $paciente->expedienteClinico->diagnostico }}
            </div>
        @endif


        @if($siguienteSesion > 1)
            <div class="flex items-center gap-1.5 mb-6">
                @for($i = 1; $i < $siguienteSesion; $i++)
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                @endfor
                <span class="w-3 h-3 rounded-full bg-[#0E9CAE]"></span>
            </div>
        @endif


        @if($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tratamientos.store') }}" class="bg-white rounded-lg shadow p-6 space-y-4">
            @csrf
            <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sesión</label>
                <input type="number" name="sesion" min="1" value="{{ old('sesion', $siguienteSesion) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de la sesión</label>
                <input type="date" name="fecha_registro" value="{{ old('fecha_registro', now()->toDateString()) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                <textarea name="descripcion" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]"
                          required>{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Avance del paciente</label>
                <textarea name="evolucion" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">{{ old('evolucion') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('pacientes.show', $paciente) }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                <button type="submit" class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                    Guardar sesión
                </button>
            </div>
        </form>
    </div>
</x-fimove-layout>