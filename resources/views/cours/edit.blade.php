@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Modifier le cours d’eau</h2>
            <a href="{{ route('cours.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour
            </a>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('cours.update', $cours['nomCoursEau']) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="nomCoursEau" class="block text-sm font-medium text-gray-700">Nom du cours d’eau</label>
                    <input type="text" name="nomCoursEau" value="{{ $cours['nomCoursEau'] ?? '' }}" readonly
                        class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm text-gray-700 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Longueur (km)</label>
                    <input type="number" step="0.1" name="longueurCoursEau" value="{{ $cours['longueurCoursEau'] ?? '' }}"
                        required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Débit Moyen (m³/s)</label>
                    <input type="number" step="0.1" name="debitMoyenCoursEau" value="{{ $cours['debitMoyenCoursEau'] ?? '' }}"
                        required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Régime hydrologique</label>
                    <input type="text" name="regimehydrologique" value="{{ $cours['regimehydrologique'] ?? '' }}"
                        required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom de la source</label>
                    <input type="text" name="nomSource" value="{{ $cours['nomSource'] ?? '' }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom du versement</label>
                    <input type="text" name="nomVersement" value="{{ $cours['nomVersement'] ?? '' }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                        <i data-lucide="save" class="w-4 h-4"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Activer les icônes Lucide -->
@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection
