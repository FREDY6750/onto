<?php

namespace App\Http\Controllers;

use App\Services\Neo4jService;
use Illuminate\Http\Request;

class CoursEauController extends Controller
{
    protected $neo4j;

    public function __construct(Neo4jService $neo4j)
    {
        $this->neo4j = $neo4j;
    }

    public function index()
    {
        $results = $this->neo4j->getAllCoursEau();

        $coursEaux = [];

        foreach ($results as $record) {
            $cours = $record->get('c');
            $bassin = $record->get('b');
            $localites = $record->get('l');

            $coursNode = $cours instanceof \Laudis\Neo4j\Types\Node ? $cours->getProperties() : [];
            $bassinNom = ($bassin instanceof \Laudis\Neo4j\Types\Node) ? $bassin->getProperties()['nomBassinVersant'] ?? 'N/A' : 'N/A';


            $localiteList = [];
            if (is_iterable($localites)) {
                foreach ($localites as $l) {
                    if ($l instanceof \Laudis\Neo4j\Types\Node) {
                        $localiteList[] = $l->getProperties()['nomLocGeo'] ?? '';
                    }
                }
            }

            $coursEaux[] = [
                'nomCoursEau' => $coursNode['nomCoursEau'] ?? 'N/A',
                'longueurCoursEau' => $coursNode['longueurCoursEau'] ?? 'N/A',
                'debitMoyenCoursEau' => $coursNode['debitMoyenCoursEau'] ?? 'N/A',
                'regimehydrologique' => $coursNode['regimehydrologique'] ?? 'N/A',
                'nomSource' => $coursNode['nomSource'] ?? 'N/A',
                'nomVersement' => $coursNode['nomVersement'] ?? 'N/A',
                'bassin' => $bassinNom,
                'localites' => $localiteList,
            ];
        }

        return view('cours.index', compact('coursEaux'));
    }

    public function create()
    {
        $localites = $this->neo4j->getAllLocalites();
        $bassins = $this->neo4j->getAllBassins();

        return view('cours.create', compact('localites', 'bassins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomCoursEau' => 'required',
            'longueurCoursEau' => 'required|numeric',
            'debitMoyenCoursEau' => 'required|numeric',
            'regimehydrologique' => 'required',
            'nomSource' => 'required|string',
            'nomVersement' => 'required|string',
            'localite_id' => 'required',
            'bassin_id' => 'required',
        ]);

        $this->neo4j->createCoursEau($validated);

        return redirect()->route('cours.index')->with('success', 'Cours d\'eau ajouté !');
    }

    public function show($name)
    {
        $record = $this->neo4j->findCoursEauByName($name);

        $cours = $record->get('c')->getProperties();
        $cours['bassin'] = optional($record->get('b'))->getProperties() ?? [];
        $cours['localites'] = [];

        $localites = $record->get('l');
        if (is_iterable($localites)) {
            foreach ($localites as $l) {
                if ($l instanceof \Laudis\Neo4j\Types\Node) {
                    $cours['localites'][] = $l->getProperties()['nomLocGeo'] ?? '';
                }
            }
        }

        return view('cours.show', compact('cours'));
    }

    public function edit($name)
    {
        $record = $this->neo4j->findCoursEauByName($name);
        $cours = $record->get('c')->getProperties();
        $localites = $this->neo4j->getAllLocalites();
        $bassins = $this->neo4j->getAllBassins();

        return view('cours.edit', compact('cours', 'localites', 'bassins'));
    }

    public function update(Request $request, $name)
    {
        $validated = $request->validate([
            'nomCoursEau' => 'required',
            'longueurCoursEau' => 'required|numeric',
            'debitMoyenCoursEau' => 'required|numeric',
            'regimehydrologique' => 'required',
            'nomSource' => 'required|string',
            'nomVersement' => 'required|string',
            'localite_id' => 'required',
            'bassin_id' => 'required',
        ]);

        $this->neo4j->updateCoursEau($name, $validated);

        return redirect()->route('cours.index')->with('success', 'Cours d\'eau mis à jour !');
    }

    public function destroy($name)
    {
        $this->neo4j->deleteCoursEau($name);
        return redirect()->route('cours.index')->with('success', 'Cours d\'eau supprimé.');
    }
}
