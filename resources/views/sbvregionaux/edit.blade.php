@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
  <h3 class="text-xl font-bold mb-6 text-gray-800">Modifier le sous-bassin régional</h3>

  @if ($errors->any())
    <div class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-red-700">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('sbv_regionaux.update', ['nomSousBassinVersant' => $sbv['nomSousBassinVersant']]) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block font-medium text-sm text-gray-700">Nom du sous-bassin régional</label>
      <input type="text" name="nomSousBassinVersant" value="{{ old('nomSousBassinVersant', $sbv['nomSousBassinVersant'] ?? '') }}"
             class="mt-1 block w-full rounded border-gray-300 shadow-sm">
      <p class="text-xs text-gray-500 mt-1">Laisser vide pour conserver l’ancien nom.</p>
    </div>

    <div class="text-end pt-2">
      <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Mettre à jour</button>
      <a href="{{ route('sbv_regionaux.index') }}" class="ml-2 text-sm text-gray-600 hover:underline">Annuler</a>
    </div>
  </form>
</div>
@endsection
