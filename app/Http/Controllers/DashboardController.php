<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Neo4jService;

class DashboardController extends Controller
{
    protected $neo4j;

    public function __construct(Neo4jService $neo4j)
    {
        $this->neo4j = $neo4j;
    }

    public function index()
{
    $coursCount = $this->neo4j->getClient()->run('MATCH (:CoursEau) RETURN count(*) AS total')->first()->get('total');
    $affluentCount = $this->neo4j->getClient()->run('MATCH (:Affluent) RETURN count(*) AS total')->first()->get('total');
    $bassinCount = $this->neo4j->getClient()->run('MATCH (:BassinVersant) RETURN count(*) AS total')->first()->get('total');
    $localiteCount = $this->neo4j->getClient()->run('MATCH (n:LocaliteGeographique) WHERE n.nomLocGeo IS NOT NULL OR n.nomVille IS NOT NULL RETURN count(n) AS total')->first()->get('total');
    $villeCount = $this->neo4j->getClient()->run('MATCH (:Ville) RETURN count(*) AS total')->first()->get('total');

    return view('dashboard', compact('coursCount', 'affluentCount', 'bassinCount', 'localiteCount', 'villeCount'));
}

}
