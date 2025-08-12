@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Liste des Fleuves</h2>
    <a href="{{ route('fleuve.create') }}" class="btn btn-primary mb-3">Ajouter</a>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fleuves as $record)
                <tr>
                    <td>{{ $record['f']->getProperties()['nomFleuve'] }}</td>
                    <td>
                        <a href="{{ route('fleuve.edit', $record['f']->getProperties()['nomFleuve']) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('fleuve.destroy', $record['f']->getProperties()['nomFleuve']) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
