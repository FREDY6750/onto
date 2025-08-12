@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <h3 class="text-xl font-bold mb-6 text-gray-800">Modifier le Bassin Versant National</h3>
    <form method="POST" action="{{ route('bv_nationaux.update', $bassin['nomBassinVersant']) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block font-medium text-sm text-gray-700">Nom du Bassin</label>
            <input type="text" name="nomBassinVersant" value="{{ $bassin['nomBassinVersant'] }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
        </div>

        <div>
            <label class="block font-medium text-sm text-gray-700">Superficie (km²)</label>
            <input type="number" step="0.1" name="superficieBassinVersant" value="{{ $bassin['superficieBassinVersant'] }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
        </div>

        <div>
            <label class="block font-medium text-sm text-gray-700">Part nationale (%)</label>
            <input type="number" step="0.1" name="partSurfaceNationaleBassinVersant" value="{{ $bassin['partSurfaceNationaleBassinVersant'] }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
        </div>

        <div class="text-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Mettre à jour</button>
            <a href="{{ route('bv_nationaux.index') }}" class="ml-2 text-sm text-gray-500 hover:underline">Annuler</a>
        </div>
    </form>
</div>
@endsection
