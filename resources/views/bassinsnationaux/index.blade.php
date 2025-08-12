@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="mountain-snow" class="w-6 h-6 text-blue-600"></i> Bassins Versants Nationaux
        </h2>
        <a href="{{ route('bv_nationaux.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Ajouter
        </a>
    </div>

    <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-50 text-left text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Superficie (km²)</th>
                    <th class="px-4 py-3">Part nationale (%)</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse ($bvNationaux as $bv)
                    <tr>
                        <td class="px-4 py-3">{{ $bv['nomBassinVersant'] }}</td>
                        <td class="px-4 py-3">{{ $bv['superficieBassinVersant'] }}</td>
                        <td class="px-4 py-3">{{ $bv['partSurfaceNationaleBassinVersant'] }}</td>
                        <td class="px-4 py-3 flex items-center gap-3">
                            <a href="{{ route('bv_nationaux.edit', $bv['nomBassinVersant']) }}" class="text-indigo-600 hover:text-indigo-800">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <form method="POST" action="{{ route('bv_nationaux.destroy', $bv['nomBassinVersant']) }}" onsubmit="return confirm('Supprimer ce bassin ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-800">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-6 text-gray-500">Aucun bassin versant national trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
