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
    $client = $this->neo4j->getClient();

    // Helper : convertit un entier Neo4j en int PHP
    $toInt = function ($v) {
        return (is_object($v) && method_exists($v, 'toInt')) ? $v->toInt() : (int) $v;
    };

    // Comptages
    $coursCount = $toInt(
        $client->run('MATCH (:CoursEau) RETURN count(*) AS total')->first()->get('total')
    );

    $riviereCount = $toInt(
        $client->run('MATCH (:Riviere) RETURN count(*) AS total')->first()->get('total')
    );

    $affluentCount = $toInt(
        $client->run('MATCH (:Affluent) RETURN count(*) AS total')->first()->get('total')
    );

    $bassinCount = $toInt(
        $client->run('MATCH (:BassinVersant) RETURN count(*) AS total')->first()->get('total')
    );

    // Localités : on garde ta logique (nomLocGeo ou nomVille défini)
    $localiteCount = $toInt(
        $client->run('MATCH (n:LocaliteGeographique) WHERE n.nomLocGeo IS NOT NULL OR n.nomVille IS NOT NULL RETURN count(n) AS total')->first()->get('total')
    );

    // Bassins versants nationaux (BV nationaux) – adapte le label si différent chez toi
    //$bvNationalCount = $toInt(
    //    $client->run('MATCH (:BassinVersantNational) RETURN count(*) AS total')->first()->get('total')
    //);

    // Sous-bassins versants nationaux (SBV nationaux) – d’après tes services précédents
    $sbvNationalCount = $toInt(
        $client->run('MATCH (:SousBassinVersantNational) RETURN count(*) AS total')->first()->get('total')
    );

    // Sous-bassins versants régionaux (SBV régionaux) – adapte le label si besoin
    $sbvRegionalCount = $toInt(
        $client->run('MATCH (:SousBassinVersantRegional) RETURN count(*) AS total')->first()->get('total')
    );

    return view('dashboard', compact(
        'coursCount',
        'riviereCount',
        'affluentCount',
        'bassinCount',
        'localiteCount',
       // 'bvNationalCount',
        'sbvNationalCount',
        'sbvRegionalCount'
    ));
}


}
