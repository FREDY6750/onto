@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="mountain" class="w-6 h-6 text-purple-600"></i> Bassins Versants
        </h2>
        <a href="{{ route('bassins.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition">
            <i data-lucide="plus" class="w-4 h-4"></i> Ajouter un bassin
        </a>
    </div>

    <div class="bg-white shadow rounded-xl overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs text-left">
                <tr>
                    <th class="px-6 py-3">Nom</th>
                    <th class="px-6 py-3">Superficie (km²)</th>
                    <th class="px-6 py-3">Part sur le territoire (%)</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse ($bassins as $bassin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $bassin['nomBassinVersant'] ?? 'N/A' }}</td>
                        <td class="px-6 py-3">{{ $bassin['superficieBassinVersant'] ?? 'N/A' }}</td>
                        <td class="px-6 py-3">{{ $bassin['partSurfaceNationaleBassinVersant'] ?? 'N/A' }}</td>
                        <td class="px-6 py-3">
                            <div class="flex gap-3">
                                <a href="{{ route('bassins.edit', $bassin['nomBassinVersant']) }}"
                                   class="text-indigo-600 hover:text-indigo-800" title="Modifier">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                                </a>
                                <form action="{{ route('bassins.destroy', $bassin['nomBassinVersant']) }}"
                                      method="POST"
                                      onsubmit="return confirm('Supprimer ce bassin ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer" class="text-red-600 hover:text-red-800">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-400">Aucun bassin versant trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection
