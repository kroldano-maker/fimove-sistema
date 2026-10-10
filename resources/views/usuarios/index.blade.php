<x-fimove-layout title="Usuarios — Clínica FIMOVE">
    <div class="max-w-4xl mx-auto py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Usuarios</h1>
            <a href="{{ route('usuarios.create') }}"
               class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                + Nuevo usuario
            </a>
        </div>

        @if(session('status'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 px-4 py-2 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#0E9CAE]">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Nombre</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Correo</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Rol</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-white uppercase">Estado</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($usuarios as $usuario)
                        <tr class="{{ ! $usuario->estado ? 'opacity-50' : '' }}">
                            <td class="px-4 py-2 text-sm text-gray-800">
                                {{ $usuario->name }} {{ $usuario->apellido }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600">{{ $usuario->email }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600">{{ $roles[$usuario->rol] ?? $usuario->rol }}</td>
                            <td class="px-4 py-2 text-sm">
                                @if($usuario->estado)
                                    <span class="inline-block px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs">Activo</span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded-full bg-gray-200 text-gray-600 text-xs">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right text-sm whitespace-nowrap">
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="text-blue-600 hover:underline">Editar</a>

                                @if($usuario->id !== auth()->id())
                                    @if($usuario->estado)
                                        <form id="form-desactivar-usuario-{{ $usuario->id }}" method="POST" action="{{ route('usuarios.destroy', $usuario) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                                @click="$dispatch('confirmar-eliminar', { formId: 'form-desactivar-usuario-{{ $usuario->id }}', mensaje: '¿Desactivar a {{ $usuario->name }}? No podrá iniciar sesión hasta que lo actives de nuevo.' })"
                                                class="text-red-600 hover:underline ml-3">
                                            Desactivar
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('usuarios.activar', $usuario) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:underline ml-3">Activar</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-fimove-layout>