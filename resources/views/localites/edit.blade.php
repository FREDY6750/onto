{{-- resources/views/localites/edit.blade.php --}}
@extends('layouts.app')

@section('content')
@php
    // Le contrôleur fournit ['nomLocGeo' => '...']
    $nomActuel = $localites['nomLocGeo'] ?? '';
@endphp

<div class="max-w-3xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="pencil" class="w-6 h-6 text-blue-600"></i>
        Modifier la localité
    </h2>

    @if (session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 border border-red-200">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('localites.update', ['localite' => rawurlencode($nomActuel)]) }}"
          method="POST"
          class="bg-white shadow-md rounded-xl p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm text-gray-700 mb-1">Nom de la localité</label>
            <input type="text"
                   name="nomLocGeo"
                   value="{{ old('nomLocGeo', $nomActuel) }}"
                   required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('nomLocGeo')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">
                Astuce : si tu changes ce nom, le contrôleur utilisera l’ancien comme clé dans l’URL
                et mettra à jour le nœud Neo4j avec le nouveau.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> Mettre à jour
            </button>
            <a href="{{ route('localites.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                Annuler
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script> lucide.createIcons(); </script>
@endpush
@endsection
