<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SousBassinNationalService;

class SousBassinNationalController extends Controller
{
    public function __construct(private SousBassinNationalService $service) {}

    /**
     * Liste
     */
    public function index(Request $request)
    {
        $sbvnationaux = $this->service->all(); // array<array>
        // (Optionnel) Filtre simple côté PHP
        if ($request->filled('q')) {
            $q = mb_strtolower($request->string('q'));
            $sbvnationaux = array_values(array_filter($sbvnationaux, function ($row) use ($q) {
                return str_contains(mb_strtolower($row['nomBassinVersant'] ?? ''), $q);
            }));
        }

        return view('sbvnationaux.index', compact('sbvnationaux'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('sbvnationaux.create');
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomBassinVersant'                  => ['required', 'string', 'max:255'],
            'superficieBassinVersant'           => ['nullable', 'numeric'],
            'partSurfaceNationaleBassinVersant' => ['nullable', 'numeric'],
        ]);

        $this->service->create($data);

        return redirect()
            ->route('sbvnationaux.index')
            ->with('status', 'Sous-bassin national créé.');
    }

    /**
     * Détail
     */
    public function show(string $sbvnationaux) {
    $nom = urldecode($sbvnationaux);
    $sbv = $this->service->find($nom);
    abort_if(!$sbv, 404);
    return view('sbvnationaux.show', compact('sbv'));
}

public function edit(string $sbvnationaux) {
    $nom = urldecode($sbvnationaux);
    $sbv = $this->service->find($nom);
    abort_if(!$sbv, 404);
    return view('sbvnationaux.edit', compact('sbv'));
}

public function update(Request $request, string $sbvnationaux) {
    $ancienNom = urldecode($sbvnationaux);
    $data = $request->validate([
        'nomBassinVersant'                  => ['nullable','string','max:255'],
        'superficieBassinVersant'           => ['nullable','numeric'],
        'partSurfaceNationaleBassinVersant' => ['nullable','numeric'],
    ]);
    $this->service->update($ancienNom, $data);
    return redirect()->route('sbvnationaux.index')->with('status','Sous-bassin national mis à jour.');
}

public function destroy(string $sbvnationaux) {
    $nom = urldecode($sbvnationaux);
    $this->service->delete($nom);
    return redirect()->route('sbvnationaux.index')->with('status','Sous-bassin national supprimé.');
}

}
