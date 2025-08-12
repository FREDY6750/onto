@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="mountain" class="w-6 h-6 text-purple-600"></i>
        Modifier le Bassin Versant
    </h2>

    <form method="POST" action="{{ route('bassins.update', $bassin['nomBassinVersant']) }}" class="bg-white p-6 rounded-xl shadow space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du Bassin</label>
            <input type="text" name="nomBassinVersant"
                   class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                   value="{{ $bassin['nomBassinVersant'] }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Superficie (km²)</label>
            <input type="number" step="0.1" name="superficieBassinVersant"
                   class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                   value="{{ $bassin['superficieBassinVersant'] }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Part dans la surface nationale (%)</label>
            <input type="number" step="0.1" name="partSurfaceNationaleBassinVersant"
                   class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                   value="{{ $bassin['partSurfaceNationaleBassinVersant'] }}" required>
        </div>

        <div class="flex justify-between items-center pt-4">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition">
                <i data-lucide="save" class="w-4 h-4"></i> Mettre à jour
            </button>
            <a href="{{ route('bassins.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                <i data-lucide="x" class="w-4 h-4"></i> Annuler
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection
