<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laudis\Neo4j\ClientBuilder;

class BassinVersantNational
{
    protected $client;

    public function __construct()
    {
        $this->client = app('Neo4jClient');
    }

    public function all()
    {
        $query = 'MATCH (b:BassinVersantNational) RETURN b';
        return $this->client->run($query)->toArray();
    }

    public function find($nom)
    {
        $query = 'MATCH (b:BassinVersantNational {nomBassinVersant: $nom}) RETURN b';
        return $this->client->run($query, ['nom' => $nom])->first();
    }

    public function create($data)
    {
        $query = '
            MERGE (bv:BassinVersant {nomBassinVersant: $nom})
            MERGE (bvn:BassinVersantNational {
                nomBassinVersant: $nom,
                superficie: $superficie,
                partNationale: $part
            })
            MERGE (bvn)-[:SUBCLASS_OF]->(bv)
        ';
        $this->client->run($query, [
            'nom' => $data['nomBassinVersant'],
            'superficie' => $data['superficie'],
            'part' => $data['part']
        ]);
    }

    public function update($nom, $data)
    {
        $query = '
            MATCH (b:BassinVersantNational {nomBassinVersant: $nom})
            SET b.superficie = $superficie,
                b.partNationale = $part
        ';
        $this->client->run($query, [
            'nom' => $nom,
            'superficie' => $data['superficie'],
            'part' => $data['part']
        ]);
    }

    public function delete($nom)
    {
        $query = 'MATCH (b:BassinVersantNational {nomBassinVersant: $nom}) DETACH DELETE b';
        $this->client->run($query, ['nom' => $nom]);
    }
}
