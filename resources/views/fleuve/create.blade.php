@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Ajouter un Fleuve</h2>
    <form method="POST" action="{{ route('fleuve.store') }}">
        @csrf
        <input type="text" name="nomFleuve" class="form-control mb-2" placeholder="Nom du fleuve" required>
        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
</div>
@endsection
