<?php
namespace App\Services;

use Laudis\Neo4j\ClientBuilder;


class FleuveService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->withDriver('default', 'neo4j://neo4j:Admin123@localhost:7687')
            ->build();
    }

    public function all()
    {
        return $this->client->run('MATCH (f:Fleuve) RETURN f')->toArray();
    }

    public function create(array $data)
    {
        $this->client->run('CREATE (f:Fleuve {nomFleuve: $nom})', [
            'nom' => $data['nomFleuve']
        ]);
    }

    public function find($nom)
    {
        return $this->client->run('MATCH (f:Fleuve {nomFleuve: $nom}) RETURN f LIMIT 1', [
            'nom' => $nom
        ])->first();
    }

    public function update($nom, array $data)
    {
        $this->client->run('MATCH (f:Fleuve {nomFleuve: $nom}) SET f.nomFleuve = $nouveau', [
            'nom' => $nom,
            'nouveau' => $data['nomFleuve']
        ]);
    }

    public function delete($nom)
    {
        $this->client->run('MATCH (f:Fleuve {nomFleuve: $nom}) DETACH DELETE f', [
            'nom' => $nom
        ]);
    }
}
