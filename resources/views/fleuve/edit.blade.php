@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Modifier Fleuve</h2>
    <form method="POST" action="{{ route('fleuve.update', $fleuve['f']->getProperties()['nomFleuve']) }}">
        @csrf @method('PUT')
        <input type="text" name="nomFleuve" class="form-control mb-2"
               value="{{ $fleuve['f']->getProperties()['nomFleuve'] }}">
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
