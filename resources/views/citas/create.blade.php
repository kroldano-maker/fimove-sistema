<x-fimove-layout title="Agendar cita — Clínica FIMOVE">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Agendar cita</h1>

    @if ($errors->any())
        <div class="max-w-2xl mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('citas.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Paciente</label>
                    <select name="paciente_id" class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                        <option value="">Seleccionar paciente...</option>
                        @foreach ($pacientes as $paciente)
                            <option value="{{ $paciente->id }}" @selected(old('paciente_id') == $paciente->id)>
                                {{ $paciente->nombres }} {{ $paciente->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Fecha</label>
                    <input type="date" name="fecha" value="{{ old('fecha', $fecha) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Hora</label>
                    <input type="time" name="hora" value="{{ old('hora') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Motivo</label>
                    <input type="text" name="motivo" value="{{ old('motivo') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Estado</label>
                    <select name="estado" class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                        <option value="pendiente" @selected(old('estado', 'pendiente') == 'pendiente')>Pendiente</option>
                        <option value="confirmada" @selected(old('estado') == 'confirmada')>Confirmada</option>
                        <option value="cancelada" @selected(old('estado') == 'cancelada')>Cancelada</option>
                        <option value="atendida" @selected(old('estado') == 'atendida')>Atendida</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 mt-2 border-t border-gray-100">
                <button type="submit"
                        class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                    Guardar cita
                </button>
                <a href="{{ route('citas.index', ['fecha' => $fecha]) }}" class="text-sm text-gray-600 px-4 py-2 hover:text-gray-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-fimove-layout>