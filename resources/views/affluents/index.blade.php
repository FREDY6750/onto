@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="flowing-water" class="w-6 h-6 text-blue-600"></i> Liste des Affluents
        </h2>
        <a href="{{ route('affluents.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Ajouter un Affluent
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-xl shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs text-left">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Longueur (km)</th>
                    <th class="px-4 py-3">Débit (m³/s)</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse ($affluents as $a)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $a['nomAffluent'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $a['longueurAffluent'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $a['debitAffluent'] ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('affluents.destroy', $a['nomAffluent']) }}" method="POST" onsubmit="return confirm('Supprimer cet affluent ?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm text-red-600 hover:text-red-800 hover:underline transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i> Supprimer
                                </button>
                            </form>
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
<script>
    lucide.createIcons();
</script>
@endpush
@endsection
