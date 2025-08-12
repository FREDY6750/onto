@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="edit" class="w-6 h-6 text-indigo-600"></i> Modifier l'affluent : {{ $affluent['nomAffluent'] ?? '' }}
    </h2>

    <form method="POST" action="{{ route('affluents.update', $affluent['nomAffluent']) }}" class="bg-white p-6 rounded-xl shadow space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom (non modifiable)</label>
            <input type="text" class="w-full bg-gray-100 border border-gray-300 text-gray-500 rounded-md px-4 py-2" 
                   value="{{ $affluent['nomAffluent'] ?? '' }}" disabled>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Longueur (km)</label>
            <input type="number" step="0.1" name="longueurAffluent" required
                   class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="{{ old('longueurAffluent', $affluent['longueurAffluent'] ?? '') }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Débit (m³/s)</label>
            <input type="number" step="0.1" name="debitAffluent" required
                   class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="{{ old('debitAffluent', $affluent['debitAffluent'] ?? '') }}">
        </div>

        <div class="flex justify-between items-center pt-4">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                <i data-lucide="save" class="w-4 h-4"></i> Mettre à jour
            </button>
            <a href="{{ route('affluents.index') }}"
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
