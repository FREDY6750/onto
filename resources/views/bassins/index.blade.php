@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="mountain" class="w-6 h-6 text-blue-600"></i>
        Bassins versants
    </h2>

    <div class="mb-4 flex items-center justify-between">
        {{-- Recherche --}}
        <form method="GET" action="{{ route('bassins.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Rechercher un bassin…"
                   class="border rounded-lg px-3 py-2 w-64">
            <button class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i> Chercher
            </button>
        </form>

        {{-- Ajouter --}}
        <a href="{{ route('bassins.create') }}"
           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Ajouter un bassin
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 border border-green-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 text-left text-sm text-gray-600">
                <tr>
                    <th class="px-6 py-3 font-semibold">Nom</th>
                    <th class="px-6 py-3 font-semibold">Superficie (km²)</th>
                    <th class="px-6 py-3 font-semibold">Part sur le territoire (%)</th>
                    <th class="px-6 py-3 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse ($bassins as $bassin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $bassin['nomBassinVersant'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $bassin['superficieBassinVersant'] ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $bassin['partSurfaceNationaleBassinVersant'] ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                {{-- Voir (si la route existe) --}}
                                @if (Route::has('bassins.show'))
                                <a href="{{ route('bassins.show', ['bassin' => rawurlencode($bassin['nomBassinVersant'])]) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200"
                                   title="Voir">
                                    <i class="lucide lucide-eye w-5 h-5"></i>
                                </a>
                                @endif

                                {{-- Modifier --}}
                                <a href="{{ route('bassins.edit', ['bassin' => rawurlencode($bassin['nomBassinVersant'])]) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 bg-yellow-100 text-yellow-600 rounded-full hover:bg-yellow-200"
                                   title="Modifier">
                                    <i class="lucide lucide-pencil w-5 h-5"></i>
                                </a>

                                {{-- Supprimer --}}
                                <form action="{{ route('bassins.destroy', ['bassin' => rawurlencode($bassin['nomBassinVersant'])]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Supprimer ce bassin ?')">
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
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Aucun bassin versant trouvé.</td>
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
