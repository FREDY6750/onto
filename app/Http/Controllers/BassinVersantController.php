<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Neo4jService;

class BassinVersantController extends Controller
{
    protected $neo4j;

    public function __construct(Neo4jService $neo4j)
    {
        $this->neo4j = $neo4j;
    }

    public function index()
{
    $records = $this->neo4j->getAllBassins();

    $bassins = [];
    foreach ($records as $record) {
        $b = $record->get('b');
        if ($b instanceof \Laudis\Neo4j\Types\Node) {
            $bassins[] = $b->getProperties();
        }
    }

    return view('bassins.index', compact('bassins'));
}


    public function create()
    {
        return view('bassins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomBassinVersant' => 'required',
            'superficieBassinVersant' => 'required|numeric',
            'partSurfaceNationaleBassinVersant' => 'required|numeric',
        ]);

        $this->neo4j->createBassin($request->all());

        return redirect()->route('bassins.index')->with('success', 'Bassin versant ajouté.');
    }

    public function edit($nom)
    {
        $record = $this->neo4j->findBassinByName($nom);
        $bassin = $record->get('b')->getProperties();

        return view('bassins.edit', compact('bassin'));
    }

    public function update(Request $request, $nom)
    {
        $request->validate([
            'superficieBassinVersant' => 'required|numeric',
            'partSurfaceNationaleBassinVersant' => 'required|numeric',
        ]);

        $this->neo4j->updateBassin($nom, $request->all());

        return redirect()->route('bassins.index')->with('success', 'Bassin versant modifié.');
    }

    public function destroy($nom)
    {
        $this->neo4j->deleteBassin($nom);
        return redirect()->route('bassins.index')->with('success', 'Bassin versant supprimé.');
    }
}
