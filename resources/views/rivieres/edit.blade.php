@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="waves" class="w-6 h-6 text-blue-600"></i>
        Modifier la rivière
    </h2>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('rivieres.update', rawurlencode($riviere['nomCoursEau'])) }}" class="bg-white shadow-md rounded-xl p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm text-gray-700 mb-1">Nom de la rivière</label>
            <input type="text"
                   name="nomCoursEau"
                   value="{{ old('nomCoursEau', $riviere['nomCoursEau']) }}"
                   required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('nomCoursEau') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-500 mt-1">
                Si vous modifiez ce nom, l’ancien sera utilisé comme clé d’URL et le nœud sera mis à jour avec le nouveau nom.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Type</label>
                <input type="text"
                       name="typeCoursEau"
                       value="{{ old('typeCoursEau', $riviere['typeCoursEau'] ?? '') }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Longueur (km)</label>
                <input type="number" step="0.1"
                       name="longueurCoursEau"
                       value="{{ old('longueurCoursEau', $riviere['longueurCoursEau'] ?? '') }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Débit moyen (m³/s)</label>
                <input type="number" step="0.1"
                       name="debitMoyenCoursEau"
                       value="{{ old('debitMoyenCoursEau', $riviere['debitMoyenCoursEau'] ?? '') }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Nom de la source</label>
                <input type="text"
                       name="nomSource"
                       value="{{ old('nomSource', $riviere['nomSource'] ?? '') }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-700 mb-1">Versement</label>
            <input type="text"
                   name="nomVersement"
                   value="{{ old('nomVersement', $riviere['nomVersement'] ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-center gap-2 justify-end">
            <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Mettre à jour
            </button>
            <a href="{{ route('rivieres.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
        </div>
    </form>
</div>

@push('scripts')
<script> lucide.createIcons(); </script>
@endpush
@endsection
