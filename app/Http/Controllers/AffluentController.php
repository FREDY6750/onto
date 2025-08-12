<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Neo4jService;

class AffluentController extends Controller
{
    protected $neo4j;

    public function __construct(Neo4jService $neo4j)
    {
        $this->neo4j = $neo4j;
    }

    public function index()
    {
        $results = $this->neo4j->getAllAffluents();

        $affluents = array_map(function ($record) {
            $node = $record->get('a');
            return $node->getProperties();
        }, $results);

        return view('affluents.index', compact('affluents'));
    }

    public function create()
    {
        return view('affluents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomAffluent' => 'required',
            'debitAffluent' => 'nullable|numeric',
        ]);

        $this->neo4j->createAffluent($validated);

        return redirect()->route('affluents.index')->with('success', 'Affluent ajouté.');
    }

    public function destroy($nom)
    {
        $this->neo4j->deleteAffluent($nom);
        return redirect()->route('affluents.index')->with('success', 'Affluent supprimé.');
    }
    public function edit($nom)
{
    $result = $this->neo4j->findAffluentByName($nom);
    $node = $result->get('a');
    $affluent = $node->getProperties();

    return view('affluents.edit', compact('affluent'));
}

public function update(Request $request, $nom)
{
    $validated = $request->validate([
        'longueurAffluent' => 'nullable|numeric',
        'debitAffluent' => 'nullable|numeric',
    ]);

    $this->neo4j->updateAffluent($nom, $validated);

    return redirect()->route('affluents.index')->with('success', 'Affluent mis à jour.');
}

}
