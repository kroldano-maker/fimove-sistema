<x-fimove-layout title="Nuevo usuario — Clínica FIMOVE">
    <div class="max-w-2xl mx-auto py-6">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Nuevo usuario</h1>

        @if($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('usuarios.store') }}" class="bg-white rounded-lg shadow p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="rol" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
                        <option value="">Seleccioná un rol</option>
                        @foreach($roles as $clave => $etiqueta)
                            <option value="{{ $clave }}" @selected(old('rol') === $clave)>{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
                    <p class="text-xs text-gray-400 mt-1">Mínimo 8 caracteres.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 mt-2 border-t border-gray-100">
                <a href="{{ route('usuarios.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                <button type="submit" class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                    Crear usuario
                </button>
            </div>
        </form>
    </div>
</x-fimove-layout>