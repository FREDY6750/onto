@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <h3 class="text-xl font-bold mb-6 text-gray-800">Ajouter un Bassin Versant National</h3>
    <form method="POST" action="{{ route('bv_nationaux.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium text-sm text-gray-700">Nom du Bassin</label>
            <input type="text" name="nomBassinVersant" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
        </div>

        <div>
            <label class="block font-medium text-sm text-gray-700">Superficie (km²)</label>
            <input type="number" step="0.1" name="superficieBassinVersant" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
        </div>

        <div>
            <label class="block font-medium text-sm text-gray-700">Part nationale (%)</label>
            <input type="number" step="0.1" name="partSurfaceNationaleBassinVersant" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
        </div>

        <div class="text-end">
            <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Ajouter</button>
            <a href="{{ route('bv_nationaux.index') }}" class="ml-2 text-sm text-gray-500 hover:underline">Annuler</a>
        </div>
    </form>
</div>
@endsection
