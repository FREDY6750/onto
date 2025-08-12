@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i data-lucide="map" class="w-6 h-6 text-blue-600"></i>
        Régions et Villes traversées par le Fleuve Mouhoun
    </h2>

    @foreach($grouped as $region => $villes)
        <div class="bg-white shadow-md rounded-xl mb-6 overflow-hidden">
            <div class="bg-blue-600 text-white px-6 py-3 flex justify-between items-center">
                <strong class="text-lg">
                    {{ ucfirst(str_replace('region', 'Région ', $region)) }}
                </strong>
                <a href="{{ route('localites.create') }}"
                   class="inline-flex items-center px-3 py-1.5 bg-white text-blue-600 text-sm font-medium rounded hover:bg-gray-100 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                </a>
            </div>
            <ul class="divide-y divide-gray-100">
                @foreach($villes as $ville)
                    <li class="flex items-center justify-between px-6 py-4 hover:bg-gray-50">
                        <span class="text-gray-700 font-medium">{{ $ville }}</span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('localites.edit', $ville) }}"
                               class="text-indigo-600 hover:text-indigo-800 p-2 rounded hover:bg-gray-100 transition" title="Modifier">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('localites.destroy', $ville) }}" method="POST"
                                  onsubmit="return confirm('Supprimer cette ville ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-800 p-2 rounded hover:bg-gray-100 transition"
                                        title="Supprimer">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush
@endsection
