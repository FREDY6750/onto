@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="waves" class="w-6 h-6 text-blue-600"></i>
        Rivières
    </h2>

    <div class="mb-4 flex items-center justify-between">
        <!-- Formulaire de recherche -->
        <form method="GET" action="{{ route('rivieres.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Rechercher une rivière…"
                   class="border rounded-lg px-3 py-2 w-64">
            <button class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i> Chercher
            </button>
        </form>

        <!-- Bouton Ajouter -->
        <a href="{{ route('rivieres.create') }}"
           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Nouvelle rivière
        </a>
    </div>

    @if(session('status'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 border border-green-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 text-left text-sm text-gray-600">
                <tr>
                    <th class="px-6 py-3 font-semibold">Nom</th>
                    <th class="px-6 py-3 font-semibold">Type</th>
                    <th class="px-6 py-3 font-semibold">Longueur (km)</th>
                    <th class="px-6 py-3 font-semibold">Débit (m³/s)</th>
                    <th class="px-6 py-3 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rivieres as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $r['nomCoursEau'] }}</td>
                        <td class="px-6 py-4">{{ $r['typeCoursEau'] ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $r['longueurCoursEau'] ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $r['debitMoyenCoursEau'] ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <!-- Voir -->
                                <a href="{{ route('rivieres.show', rawurlencode($r['nomCoursEau'])) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200"
                                   title="Voir">
                                    <i class="lucide lucide-eye w-5 h-5"></i>
                                </a>
                                <!-- Modifier -->
                                <a href="{{ route('rivieres.edit', rawurlencode($r['nomCoursEau'])) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 bg-indigo-100 text-indigo-600 rounded-full hover:bg-indigo-200"
                                   title="Modifier">
                                    <i class="lucide lucide-pencil w-5 h-5"></i>
                                </a>
                                <!-- Supprimer -->
                                <form action="{{ route('rivieres.destroy', rawurlencode($r['nomCoursEau'])) }}"
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
                        <td class="px-6 py-8 text-center text-gray-500" colspan="5">
                            Aucune rivière trouvée.
                        </td>
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
