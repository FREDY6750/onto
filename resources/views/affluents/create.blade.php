@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="plus-circle" class="w-6 h-6 text-blue-600"></i>
        Ajouter un affluent
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

    <form method="POST" action="{{ route('affluents.store') }}" class="bg-white p-6 rounded-xl shadow space-y-5">
        @csrf

        {{-- Nom --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input type="text" name="nomAffluent" required
                   value="{{ old('nomAffluent') }}"
                   placeholder="Ex. : Affluent X"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('nomAffluent')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Longueur (optionnel) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Longueur (km)</label>
            <input type="number" step="0.1" name="longueurAffluent"
                   value="{{ old('longueurAffluent') }}"
                   placeholder="Ex. : 45.6"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('longueurAffluent')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Débit (optionnel) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Débit (m³/s)</label>
            <input type="number" step="0.1" name="debitAffluent"
                   value="{{ old('debitAffluent') }}"
                   placeholder="Ex. : 12.3"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('debitAffluent')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end items-center gap-2 pt-4">
            <a href="{{ route('affluents.index') }}"
               class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-lucide="check" class="w-4 h-4"></i>
                Ajouter
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script> lucide.createIcons(); </script>
@endpush
@endsection
