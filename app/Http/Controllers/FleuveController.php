<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FleuveService;

class FleuveController extends Controller
{
    protected $service;

    public function __construct(FleuveService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $fleuves = $this->service->all();
        return view('fleuve.index', compact('fleuves'));
    }

    public function create()
    {
        return view('fleuve.create');
    }

    public function store(Request $request)
    {
        $this->service->create($request->all());
        return redirect()->route('fleuve.index')->with('status', 'Fleuve ajouté');
    }

    public function edit($id)
    {
        $fleuve = $this->service->find($id);
        return view('fleuve.edit', compact('fleuve'));
    }

    public function update(Request $request, $id)
    {
        $this->service->update($id, $request->all());
        return redirect()->route('fleuve.index')->with('status', 'Fleuve mis à jour');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('fleuve.index')->with('status', 'Fleuve supprimé');
    }
}
