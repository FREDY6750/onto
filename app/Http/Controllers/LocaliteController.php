<?php

namespace App\Http\Controllers;

use App\Services\Neo4jService;
use Illuminate\Http\Request;


class LocaliteController extends Controller
{
    protected $neo4j;

    public function __construct(Neo4jService $neo4j)
    {
        $this->neo4j = $neo4j;
    }

   public function index()
{
    $results = $this->neo4j->getAllLocalitesGroupedByRegion();

    $grouped = [];

    foreach ($results as $record) {
        $region = $record->get('region');
        $ville = $record->get('ville');
        if ($region && $ville) {
            $grouped[$region][] = $ville;
        }
    }

    return view('localites.index', compact('grouped'));
}



 public function create()
{
    $query = "MATCH (r:LocaliteGeographique) WHERE r.nomLocGeo IS NOT NULL RETURN r";
    $regions = $this->neo4j->getClient()->run($query)->toArray();

    return view('localites.create', compact('regions'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'nomVille' => 'required|string',
        'nomLocGeo' => 'required|string',
    ]);

    $this->neo4j->createVille($validated);

    return redirect()->route('localites.index')->with('success', 'Ville ajoutée !');
}

public function edit($nom)
{
    $record = $this->neo4j->findVille($nom);
    $villeNode = $record->get('v');
    $regionNode = $record->get('r');

    $ville = $villeNode->getProperties();
    $ville['nomLocGeo'] = $regionNode?->getProperties()['nomLocGeo'] ?? '';

    $regionsQuery = <<<CYPHER
MATCH (r:LocaliteGeographique) 
WHERE r.nomLocGeo IS NOT NULL AND NOT EXISTS(r.nomVille) 
RETURN r
CYPHER;

    $regions = $this->neo4j->getClient()->run($regionsQuery)->toArray();

    return view('localites.edit', compact('ville', 'regions'));
}

public function update(Request $request, $nomInitial)
{
    $validated = $request->validate([
        'nomVille' => 'required|string',
        'nomLocGeo' => 'required|string',
    ]);

    $this->neo4j->updateVille($nomInitial, $validated);

    return redirect()->route('localites.index')->with('success', 'Ville mise à jour avec succès.');
}

public function destroy($nom)
{
    $this->neo4j->deleteVille($nom);
    return redirect()->route('localites.index')->with('success', 'Ville supprimée avec succès.');
}

}
