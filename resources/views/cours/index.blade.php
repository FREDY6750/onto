@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    <main class="flex-1 max-w-7xl mx-auto py-10 px-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i data-lucide="water" class="w-6 h-6 text-blue-600"></i>
                Liste des Fleuves
            </h2>

            <a href="{{ route('cours.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Ajouter un Fleuve
            </a>
        </div>

        {{-- Recherche --}}
        <div class="mb-4">
            <form method="GET" action="{{ route('cours.index') }}" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Rechercher un Fleuve…"
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

        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Nom</th>
                        <th class="px-4 py-3 text-left">Longueur (km)</th>
                        <th class="px-4 py-3 text-left">Débit (m³/s)</th>
                        <th class="px-4 py-3 text-left">Régime hydrologique</th>
                        <th class="px-4 py-3 text-left">Source</th>
                        <th class="px-4 py-3 text-left">Versement</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($cours as $c)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $c['nomCoursEau'] ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $c['longueurCoursEau'] ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $c['debitMoyenCoursEau'] ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $c['typeCoursEau'] ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $c['nomSource'] ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $c['nomVersement'] ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    {{-- Voir (si la route existe) --}}
                                    @if (Route::has('cours.show'))
                                    <a href="{{ route('cours.show', rawurlencode($c['nomCoursEau'])) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200"
                                       title="Voir">
                                        <i class="lucide lucide-eye w-5 h-5"></i>
                                    </a>
                                    @endif
                                    {{-- Modifier --}}
                                    <a href="{{ route('cours.edit', rawurlencode($c['nomCoursEau'])) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 bg-indigo-100 text-indigo-600 rounded-full hover:bg-indigo-200"
                                       title="Modifier">
                                        <i class="lucide lucide-pencil w-5 h-5"></i>
                                    </a>
                                    {{-- Supprimer --}}
                                    <form action="{{ route('cours.destroy', rawurlencode($c['nomCoursEau'])) }}"
                                          method="POST"
                                          onsubmit="return confirm('Confirmer la suppression ?')">
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
                            <td colspan="7" class="px-4 py-6 text-center text-gray-400">Aucun Fleuve trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>

@push('scripts')
<script> lucide.createIcons(); </script>
@endpush
@endsection
