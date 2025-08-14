<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LocaliteGeographiqueService;

class LocaliteController extends Controller
{
    protected $service;

    public function __construct(LocaliteGeographiqueService $service)
    {
        $this->service = $service;
    }

    /**
     * Liste des localités (on passe un tableau de strings à la vue).
     */
    public function index(Request $request)
    {
        // $rows est un array de CypherMap (car toArray() dans le service)
        $rows = $this->service->all();

        // On extrait proprement nomLocGeo -> tableau de strings
        $localites = array_map(function ($row) {
            // $row est un CypherMap; on récupère le noeud 'l'
            $node = $row->get('l'); // Laudis\Neo4j\Types\Node
            return $node->getProperty('nomLocGeo'); // string
        }, $rows);

        // Filtre de recherche (sur un simple tableau de strings)
        if ($request->filled('q')) {
            $q = mb_strtolower($request->q);
            $localites = array_values(array_filter($localites, function ($nomLocGeo) use ($q) {
                return str_contains(mb_strtolower($nomLocGeo), $q);
            }));
        }

        return view('localites.index', compact('localites'));
    }

    public function create()
    {
        return view('localites.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomLocGeo' => 'required|string|max:255',
        ]);

        $this->service->create($request->only('nomLocGeo'));

        return redirect()->route('localites.index')
            ->with('success', 'Localité créée avec succès.');
    }

    public function edit($nomLocGeo)
    {
        // Attention: $nomLocGeo peut être encodé dans l’URL (espaces, etc.)
        $nomLocGeo = urldecode($nomLocGeo);

        $localite = $this->service->find($nomLocGeo);
        if (!$localite) {
            return redirect()->route('localites.index')->with('error', 'Localité introuvable.');
        }

        $nomLocGeo = $localite->get('l')->getProperty('nomLocGeo');

        return view('localites.edit', [
            'localite' => ['nomLocGeo' => $nomLocGeo],
        ]);
    }

    public function update(Request $request, $nomLocGeo)
    {
        $nomLocGeo = urldecode($nomLocGeo);

        $request->validate([
            'nomLocGeo' => 'required|string|max:255',
        ]);

        $this->service->update($nomLocGeo, $request->only('nomLocGeo'));

        return redirect()->route('localites.index')
            ->with('success', 'Localité mise à jour avec succès.');
    }

    public function destroy($nomLocGeo)
    {
        $nomLocGeo = urldecode($nomLocGeo);

        $this->service->delete($nomLocGeo);

        return redirect()->route('localites.index')
            ->with('success', 'Localité supprimée avec succès.');
    }
}
