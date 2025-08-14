@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="mountain" class="w-6 h-6 text-blue-600"></i>
        Modifier le bassin versant
    </h2>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- NOTE: si votre Route::resource('bassins', ...) utilise le paramètre par défaut "bassin",
         utilisez bien la clé ['bassin' => ...] comme ci-dessous. Sinon, mappez le paramètre. --}}
    <form method="POST" action="{{ route('bassins.update', ['bassin' => rawurlencode($bassin['nomBassinVersant'])]) }}" class="bg-white p-6 rounded-xl shadow space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du bassin</label>
            <input type="text" name="nomBassinVersant"
                   value="{{ old('nomBassinVersant', $bassin['nomBassinVersant'] ?? '') }}"
                   required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Superficie (km²)</label>
            <input type="number" step="0.1" name="superficieBassinVersant"
                   value="{{ old('superficieBassinVersant', $bassin['superficieBassinVersant'] ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Part sur le territoire (%)</label>
            <input type="number" step="0.01" name="partSurfaceNationaleBassinVersant"
                   value="{{ old('partSurfaceNationaleBassinVersant', $bassin['partSurfaceNationaleBassinVersant'] ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex justify-end items-center gap-2 pt-4">
            <a href="{{ route('bassins.index') }}"
               class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-lucide="save" class="w-4 h-4"></i>
                Mettre à jour
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script> lucide.createIcons(); </script>
@endpush
@endsection
