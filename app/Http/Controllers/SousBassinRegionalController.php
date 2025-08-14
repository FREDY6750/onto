<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SousBassinRegionalService;

class SousBassinRegionalController extends Controller
{
    public function __construct(private SousBassinRegionalService $service) {}

    public function index()
    {
        $sbvregionaux = $this->service->all();
        return view('sbvregionaux.index', compact('sbvregionaux'));
    }

    public function create()
    {
        return view('sbvregionaux.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomBassinVersant' => ['required','string','max:255'],
        ]);

        $this->service->create($data);
        return redirect()->route('sbvregionaux.index')->with('status','Sous-bassin régional créé.');
    }

    public function edit(string $nomBassinVersant)
    {
        $sbv = $this->service->find($nomBassinVersant);
        abort_if(!$sbv, 404);
        return view('sbvregionaux.edit', compact('sbv'));
    }

    public function update(Request $request, string $nomBassinVersant)
    {
        $data = $request->validate([
            'nomBassinVersant' => ['nullable','string','max:255'],
        ]);

        $this->service->update($nomBassinVersant, $data);
        return redirect()->route('sbvregionaux.index')->with('status','Sous-bassin régional mis à jour.');
    }

    public function destroy(string $nomBassinVersant)
    {
        $this->service->delete($nomBassinVersant);
        return redirect()->route('sbvregionaux.index')->with('status','Sous-bassin régional supprimé.');
    }
}
