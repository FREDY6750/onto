@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="flowing-water" class="w-6 h-6 text-blue-600"></i> Affluents
        </h2>

        <a href="{{ route('affluents.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-4 h-4"></i> Ajouter
        </a>
    </div>

    {{-- Recherche --}}
    <div class="mb-4">
        <form method="GET" action="{{ route('affluents.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Rechercher un affluent…"
                   class="border rounded-lg px-3 py-2 w-64">
            <button class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i> Chercher
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-xl shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Nom</th>
                    <th class="px-4 py-3 text-left">Longueur (km)</th>
                    <th class="px-4 py-3 text-left">Débit (m³/s)</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse ($affluents as $a)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $a['nomAffluent'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $a['longueurAffluent'] ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $a['debitAffluent'] ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                {{-- Voir (si la route existe chez toi) --}}
                                @if (Route::has('affluents.show'))
                                <a href="{{ route('affluents.show', ['affluent' => rawurlencode($a['nomAffluent'])]) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200"
                                   title="Voir">
                                    <i class="lucide lucide-eye w-5 h-5"></i>
                                </a>
                                @endif

                                {{-- Modifier --}}
                                <a href="{{ route('affluents.edit', ['affluent' => rawurlencode($a['nomAffluent'])]) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 bg-indigo-100 text-indigo-600 rounded-full hover:bg-indigo-200"
                                   title="Modifier">
                                    <i class="lucide lucide-pencil w-5 h-5"></i>
                                </a>

                                {{-- Supprimer --}}
                                <form action="{{ route('affluents.destroy', ['affluent' => rawurlencode($a['nomAffluent'])]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Supprimer cet affluent ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-9 h-9 bg-red-100 text-red-600 rounded-full hover:bg-red-200"
                                            title="Supprimer">
                                        <i class="lucide lucide-trash-2 w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">Aucun affluent trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script> lucide.createIcons(); </script>
@endpush
@endsection
