<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm text-gray-600 mb-1">Usuario</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="username" placeholder="Usuario"
                   class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-gray-400 focus:ring-0">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <label for="password" class="block text-sm text-gray-600 mb-1">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   placeholder="Contraseña"
                   class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-gray-400 focus:ring-0">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-gray-800 focus:ring-0">
            Recordarme
        </label>

        <div class="text-center">
            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full bg-black text-white text-sm font-medium py-2.5 rounded-lg hover:bg-gray-800">
            Iniciar sesión
        </button>
    </form>
</x-guest-layout>