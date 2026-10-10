<x-fimove-layout title="Editar evaluación — Clínica FIMOVE">
    <h1 class="text-xl font-semibold text-gray-800 mb-6">Editar evaluación fisioterapéutica</h1>

    @if ($errors->any())
        <div class="max-w-4xl mb-4 rounded-md bg-red-50 border border-red-200 text-red-700 px-4 py-2 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl">
        <form method="POST" action="{{ route('evaluaciones.update', $evaluacion) }}">
            @csrf
            @method('PUT')
            @include('evaluaciones._form')

            <div class="flex items-center gap-3 pb-8">
                <button type="submit" class="bg-[#0E9CAE] text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-[#0C8798]">
                    Guardar cambios
                </button>
                <a href="{{ route('evaluaciones.show', $evaluacion) }}" class="text-sm text-gray-600 px-4 py-2 hover:text-gray-800">Cancelar</a>
            </div>
        </form>
    </div>
</x-fimove-layout>