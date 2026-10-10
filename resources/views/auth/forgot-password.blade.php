<x-guest-layout>
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-lg font-semibold text-gray-800 mb-3">¿Olvidaste tu contraseña?</h1>

            <p class="text-sm text-gray-600 mb-4">
                Escribe tu correo registrado y te enviaremos un enlace para restablecer tu contraseña.
            </p>

            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 px-4 py-2 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-md border-gray-300 shadow-sm text-sm px-3 py-2 focus:border-[#0E9CAE] focus:ring-[#0E9CAE]">
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">
                        &larr; Volver al inicio de sesión
                    </a>
                    <button type="submit" class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                        Enviar enlace
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>