{{-- resources/views/sbv_regionaux/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="layers" class="w-6 h-6 text-blue-600"></i>
        Sous-bassins versants régionaux
    </h2>

    <div class="mb-4 flex items-center justify-between">
        {{-- Recherche (comme Localités) --}}
        <form method="GET" action="{{ route('sbvregionaux.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Rechercher un sous-bassin…"
                   class="border rounded-lg px-3 py-2 w-64">
            <button class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i> Chercher
            </button>
        </form>

        {{-- Bouton Ajouter --}}
        <a href="{{ route('sbvregionaux.create') }}"
           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Nouveau sous-bassin
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
                    <th class="px-6 py-3 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sbvregionaux as $sbv)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $sbv['nomBassinVersant'] }}
                        </td>
                        <td class="px-6 py-4">
                           
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-8 text-center text-gray-500" colspan="2">
                            Aucun sous-bassin régional.
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
