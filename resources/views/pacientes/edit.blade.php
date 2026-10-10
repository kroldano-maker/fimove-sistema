<x-fimove-layout title="Editar paciente — Clínica FIMOVE">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Editar paciente</h1>

    @if ($errors->any())
        <div class="max-w-5xl mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-5xl bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('pacientes.update', $paciente) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nombres</label>
                    <input type="text" name="nombres" value="{{ old('nombres', $paciente->nombres) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Apellidos</label>
                    <input type="text" name="apellidos" value="{{ old('apellidos', $paciente->apellidos) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento"
                           value="{{ old('fecha_nacimiento', optional($paciente->fecha_nacimiento)->format('Y-m-d')) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $paciente->telefono) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Correo</label>
                    <input type="email" name="correo" value="{{ old('correo', $paciente->correo) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $paciente->direccion) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Género</label>
                <select name="genero" class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                    <option value="">Seleccionar...</option>
                    <option value="M" @selected(old('genero', $paciente->genero ?? null) === 'M')>Masculino</option>
                    <option value="F" @selected(old('genero', $paciente->genero ?? null) === 'F')>Femenino</option>
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Estado civil</label>
                <select name="estado_civil" class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                    <option value="">Seleccionar...</option>
                    @foreach (['casado' => 'Casado', 'soltero' => 'Soltero', 'unido' => 'Unido', 'viudo' => 'Viudo', 'divorciado' => 'Divorciado'] as $valor => $texto)
                        <option value="{{ $valor }}" @selected(old('estado_civil', $paciente->estado_civil ?? null) === $valor)>{{ $texto }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Lateralidad</label>
                <select name="lateralidad" class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                    <option value="">Seleccionar...</option>
                    <option value="derecho" @selected(old('lateralidad', $paciente->lateralidad ?? null) === 'derecho')>Derecho</option>
                    <option value="izquierdo" @selected(old('lateralidad', $paciente->lateralidad ?? null) === 'izquierdo')>Izquierdo</option>
                </select>
            </div>

            <div class="md:col-span-1">
                <label class="block text-sm text-gray-600 mb-1">Profesión</label>
                <input type="text" name="profesion" maxlength="100" value="{{ old('profesion', $paciente->profesion ?? '') }}"
                    class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Ocupación</label>
                <input type="text" name="ocupacion" maxlength="100" value="{{ old('ocupacion', $paciente->ocupacion ?? '') }}"
                    class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
            </div>
        </div>

            <div class="flex items-center gap-3 pt-6 mt-2 border-t border-gray-100">
                <button type="submit"
                        class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                    Guardar cambios
                </button>
                <a href="{{ route('pacientes.show', $paciente) }}" class="text-sm text-gray-600 px-4 py-2 hover:text-gray-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-fimove-layout>