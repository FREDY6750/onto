<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Neo4jService;

class TestNeo4jController extends Controller
{
    public function test()
    {
        try {
            $neo = new Neo4jService();
            $client = $neo->getClient();

            // Test simple : compter les noeuds
            $result = $client->run('MATCH (n) RETURN count(n) AS total');

            $count = $result->first()->get('total');

            return response()->json([
                'status' => 'success',
                'message' => 'Connexion réussie à Neo4j',
                'total_nodes' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur de connexion Neo4j : ' . $e->getMessage()
            ], 500);
        }
    }
}
