@php
    $etiquetasRol = [
        'administrador' => 'Administrador',
        'personal_administrativo' => 'Personal administrativo',
        'fisioterapeuta' => 'Fisioterapeuta',
    ];
    $rolActual = auth()->user()->rol ?? null;
    $rolTexto = $etiquetasRol[$rolActual] ?? 'Usuario';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Clínica FIMOVE' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
        html { font-size: 17.5px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col" x-data="{}">

        {{-- Header --}}
        <header class="flex items-center justify-between px-6 py-3 border-b border-gray-200">
                <span class="font-semibold text-lg text-[#0E9CAE]">Clínica FIMOVE</span>

                        <div class="relative" x-data="{ abierto: false }">
                <button @click="abierto = !abierto" @click.outside="abierto = false"
                        class="flex items-center gap-2 focus:outline-none">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="{{ auth()->user()->name }}"
                         class="w-7 h-7 rounded-full object-cover">
                    <span class="text-sm text-gray-600">{{ $rolTexto }}</span>
                </button>

                <div x-show="abierto" x-cloak
                     class="absolute right-0 mt-1 w-44 bg-white border border-gray-200 rounded-md shadow-lg
                            py-1 text-sm z-10">
                    <div class="px-3 py-2 text-gray-500 border-b border-gray-100">
                        {{ auth()->user()->name }} {{ auth()->user()->apellido }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-gray-50">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <div class="flex flex-1">

            {{-- Sidebar --}}
            <aside class="w-56 bg-[#0E9CAE] py-4 px-3 flex flex-col gap-1 text-sm">
                <a href="{{ route('pacientes.index') }}"
                   class="px-3 py-2 rounded font-semibold {{ request()->routeIs('pacientes.*') ? 'bg-white text-[#0E9CAE]' : 'text-white hover:bg-[#0C8798]' }}">
                    Pacientes
                </a>
                <a href="{{ route('citas.index') }}"
                   class="px-3 py-2 rounded font-semibold {{ request()->routeIs('citas.*') ? 'bg-white text-[#0E9CAE]' : 'text-white hover:bg-[#0C8798]' }}">
                    Citas
                </a>
                <a href="{{ route('tratamientos.index') }}"
                   class="px-3 py-2 rounded font-semibold {{ request()->routeIs('tratamientos.*') ? 'bg-white text-[#0E9CAE]' : 'text-white hover:bg-[#0C8798]' }}">
                    Tratamientos
                </a>
                @if($rolActual !== 'fisioterapeuta')
                    <a href="{{ route('reportes.index') }}"
                       class="px-3 py-2 rounded font-semibold {{ request()->routeIs('reportes.*') ? 'bg-white text-[#0E9CAE]' : 'text-white hover:bg-[#0C8798]' }}">
                        Reportes
                    </a>
                @endif

                @if(auth()->user()->rol === 'administrador')
                    <div class="my-2 border-t border-white/25"></div>
                    <a href="{{ route('usuarios.index') }}"
                       class="px-3 py-2 rounded font-semibold {{ request()->routeIs('usuarios.*') ? 'bg-white text-[#0E9CAE]' : 'text-white hover:bg-[#0C8798]' }}">
                        Usuarios
                    </a>
                    <a href="{{ route('bitacora.index') }}"
                       class="px-3 py-2 rounded font-semibold {{ request()->routeIs('bitacora.*') ? 'bg-white text-[#0E9CAE]' : 'text-white hover:bg-[#0C8798]' }}">
                        Bitácora
                    </a>
                @endif
            </aside>

            {{-- Contenido de cada pantalla --}}
            <main class="flex-1 p-8">
                @if (session('exito'))
                    <div class="mb-4 rounded bg-green-50 border border-green-200 text-green-700 px-4 py-2 text-sm">
                        {{ session('exito') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Modal de confirmación reutilizable --}}
    <div x-data="{ visible: false, mensaje: '', formId: null }"
         @confirmar-eliminar.window="
             mensaje = $event.detail.mensaje;
             formId = $event.detail.formId;
             visible = true;
         "
         x-show="visible" x-cloak
         class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm p-6" @click.outside="visible = false">
            <h3 class="text-base font-semibold text-gray-800 mb-2">Confirmar eliminación</h3>
            <p class="text-sm text-gray-600 mb-6" x-text="mensaje"></p>
            <div class="flex justify-end gap-2">
                <button type="button" @click="visible = false"
                        class="px-4 py-2 text-sm font-medium rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="button"
                        @click="document.getElementById(formId).submit()"
                        class="px-4 py-2 text-sm font-medium rounded-md bg-red-600 text-white hover:bg-red-700">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</body>
</html>