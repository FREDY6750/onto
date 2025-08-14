<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RiviereService;

class RiviereController extends Controller
{
    public function __construct(private RiviereService $service) {}

    public function index()
    {
        $rivieres = $this->service->all();
        return view('rivieres.index', compact('rivieres'));
    }

    public function create()
    {
        return view('rivieres.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomCoursEau'        => ['required','string','max:255'],
            'typeCoursEau'       => ['nullable','string','max:255'],
            'longueurCoursEau'   => ['nullable','numeric'],
            'debitMoyenCoursEau' => ['nullable','numeric'],
            'nomSource'          => ['nullable','string'],
            'nomVersement'       => ['nullable','string'],
        ]);

        $this->service->create($data);
        return redirect()->route('rivieres.index')->with('status','Rivière créée.');
    }

    public function show(string $riviere)
    {
        $riviere = $this->service->find($riviere); // $riviere = nomCoursEau
        abort_if(!$riviere, 404);
        return view('rivieres.show', compact('riviere'));
    }

    public function edit(string $riviere)
    {
        $riviere = $this->service->find($riviere); // $riviere = nomCoursEau
        abort_if(!$riviere, 404);
        return view('rivieres.edit', compact('riviere'));
    }

    public function update(Request $request, string $riviere)
    {
        $data = $request->validate([
            'nomCoursEau'        => ['nullable','string','max:255'],
            'typeCoursEau'       => ['nullable','string','max:255'],
            'longueurCoursEau'   => ['nullable','numeric'],
            'debitMoyenCoursEau' => ['nullable','numeric'],
            'nomSource'          => ['nullable','string'],
            'nomVersement'       => ['nullable','string'],
        ]);

        $this->service->update($riviere, $data);

        return redirect()
            ->route('rivieres.show', ['riviere' => $data['nomCoursEau'] ?? $riviere])
            ->with('status','Rivière mise à jour.');
    }

    public function destroy(string $riviere)
    {
        $this->service->delete($riviere);
        return redirect()->route('rivieres.index')->with('status','Rivière supprimée.');
    }
}
