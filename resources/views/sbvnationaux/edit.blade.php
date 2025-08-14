@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier un sous-bassin national</h2>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('sbvnationaux.update', rawurlencode($sbv['nomBassinVersant'])) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Nom --}}
        <div>
            <label for="nomBassinVersant" class="block text-sm font-medium text-gray-700 mb-1">
                Nom du sous-bassin
            </label>
            <input type="text" name="nomBassinVersant" id="nomBassinVersant"
                   value="{{ old('nomBassinVersant', $sbv['nomBassinVersant'] ?? '') }}"
                   placeholder="Exemple : Sous-bassin X"
                   class="block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Superficie --}}
            <div>
                <label for="superficieBassinVersant" class="block text-sm font-medium text-gray-700 mb-1">
                    Superficie (km²)
                </label>
                <input type="number" step="0.1" name="superficieBassinVersant" id="superficieBassinVersant"
                       value="{{ old('superficieBassinVersant', $sbv['superficieBassinVersant'] ?? '') }}"
                       placeholder="Exemple : 123.45"
                       class="block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            {{-- Part nationale --}}
            <div>
                <label for="partSurfaceNationaleBassinVersant" class="block text-sm font-medium text-gray-700 mb-1">
                    Part nationale (%)
                </label>
                <input type="number" step="0.01" name="partSurfaceNationaleBassinVersant" id="partSurfaceNationaleBassinVersant"
                       value="{{ old('partSurfaceNationaleBassinVersant', $sbv['partSurfaceNationaleBassinVersant'] ?? '') }}"
                       placeholder="Exemple : 12.34"
                       class="block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>

        {{-- Boutons --}}
        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('sbvnationaux.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
               Annuler
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
