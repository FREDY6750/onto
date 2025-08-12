@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="building-2" class="w-6 h-6 text-pink-600"></i> Ajouter une ville
    </h2>

    <form method="POST" action="{{ route('localites.store') }}" class="bg-white p-6 rounded-xl shadow space-y-5">
        @csrf

        <!-- Nom de la ville -->
        <div>
            <label for="nomVille" class="block text-sm font-medium text-gray-700 mb-1">Nom de la ville</label>
            <input type="text" name="nomVille" id="nomVille" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-pink-200 focus:outline-none">
        </div>

        <!-- Région -->
        <div>
            <label for="nomLocGeo" class="block text-sm font-medium text-gray-700 mb-1">Région</label>
            <select name="nomLocGeo" id="nomLocGeo" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white text-gray-700 focus:ring focus:ring-pink-200 focus:outline-none">
                <option value="">-- Choisir une région --</option>
                @foreach($regions as $record)
                    @php
                        $regionNode = $record->get('r');
                        $nom = $regionNode?->getProperty('nomLocGeo') ?? '';
                    @endphp
                    @if($nom)
                        <option value="{{ $nom }}">{{ ucfirst(str_replace('region', 'Région ', $nom)) }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Bouton -->
        <div class="text-right">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2 bg-pink-600 text-white text-sm font-medium rounded-md hover:bg-pink-700 transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Ajouter
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection
