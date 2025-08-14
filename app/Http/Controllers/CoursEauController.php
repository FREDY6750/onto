<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CoursEauService;

class CoursEauController extends Controller
{
    public function __construct(private CoursEauService $service) {}

    /**
     * Liste des cours d’eau
     */
    public function index()
    {
        $rows = $this->service->all();
        // On convertit les records Neo4j en tableaux associatifs pour la vue
        $cours = array_map(
            fn($row) => $row->get('c')->getProperties(),
            $rows
        );

        return view('cours.index', compact('cours'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('cours.create');
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomCoursEau'         => ['required','string','max:255'],
            'typeCoursEau'        => ['nullable','string','max:255'],
            'longueurCoursEau'    => ['nullable','numeric'],
            'debitMoyenCoursEau'  => ['nullable','numeric'],
            'nomSource'           => ['nullable','string'],
            'nomVersement'        => ['nullable','string'],
        ]);

        $this->service->create($data);

        return redirect()
            ->route('cours.index')
            ->with('status', 'Cours d’eau créé avec succès.');
    }

    /**
     * Formulaire d’édition
     *
     * @param string $nomCoursEau  (paramètre de route)
     */
    public function edit(string $nomCoursEau)
    {
        $record = $this->service->find($nomCoursEau);
        abort_if(!$record, 404, 'Cours d’eau introuvable');

        $cours = $record->get('c')->getProperties();
        return view('cours.edit', compact('cours'));
    }

    /**
     * Mise à jour
     *
     * @param string $nomCoursEau  (paramètre de route)
     */
    public function update(Request $request, string $nomCoursEau)
    {
        $data = $request->validate([
            'nomCoursEau'         => ['nullable','string','max:255'], // laisser vide pour garder l’ancien nom
            'typeCoursEau'        => ['nullable','string','max:255'],
            'longueurCoursEau'    => ['nullable','numeric'],
            'debitMoyenCoursEau'  => ['nullable','numeric'],
            'nomSource'           => ['nullable','string'],
            'nomVersement'        => ['nullable','string'],
        ]);

        $this->service->update($nomCoursEau, $data);

        // Si le nom a été changé, on redirige vers l’index (pas de show)
        return redirect()
            ->route('cours.index')
            ->with('status', 'Cours d’eau mis à jour avec succès.');
    }

    /**
     * Suppression
     *
     * @param string $nomCoursEau  (paramètre de route)
     */
    public function destroy(string $nomCoursEau)
    {
        $this->service->delete($nomCoursEau);

        return redirect()
            ->route('cours.index')
            ->with('status', 'Cours d’eau supprimé avec succès.');
    }
}
