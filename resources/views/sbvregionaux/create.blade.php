@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="layers" class="w-6 h-6 text-blue-600"></i>
        Ajouter un sous-bassin régional
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

    <form method="POST" action="{{ route('sbvregionaux.store') }}" class="space-y-6">
        @csrf

        {{-- Nom du sous-bassin (aligne avec index/route : nomBassinVersant) --}}
        <div>
            <label for="nomBassinVersant" class="block text-sm font-medium text-gray-700 mb-1">
                Nom du sous-bassin régional
            </label>
            <input type="text"
                   name="nomBassinVersant"
                   id="nomBassinVersant"
                   value="{{ old('nomBassinVersant') }}"
                   placeholder="Exemple : Sous-bassin régional X"
                   required
                   class="block w-full rounded-lg border border-gray-300 shadow-sm px-3 py-2
                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            @error('nomBassinVersant')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('sbvregionaux.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
               Annuler
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Créer
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script> lucide.createIcons(); </script>
@endpush
@endsection
