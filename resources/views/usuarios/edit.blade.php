<x-fimove-layout title="Editar usuario — Clínica FIMOVE">
    <div class="max-w-2xl mx-auto py-6">
        <h1 class="text-xl font-semibold text-gray-800 mb-6">Editar usuario</h1>

        @if($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $usuario->name) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                    <input type="text" name="apellido" value="{{ old('apellido', $usuario->apellido) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="rol" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]" required>
                        @foreach($roles as $clave => $etiqueta)
                            <option value="{{ $clave }}" @selected(old('rol', $usuario->rol) === $clave)>{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="border-gray-200 my-5">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                    <input type="password" name="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                    <p class="text-xs text-gray-400 mt-1">Dejar en blanco si no quieres cambiarla.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 mt-2 border-t border-gray-100">
                <a href="{{ route('usuarios.index') }}" class="text-sm text-gray-600 hover:underline">Cancelar</a>
                <button type="submit" class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</x-fimove-layout>