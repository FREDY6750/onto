<?php
namespace App\Services;

use Laudis\Neo4j\ClientBuilder;

class BassinVersantService
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
        $query = 'MATCH (b:BassinVersant) RETURN b';
        return $this->client->run($query)->toArray();
    }

    public function create($nom)
    {
        $this->client->run('CREATE (:BassinVersant {nomBassinVersant: $nom})', ['nom' => $nom]);
    }

    public function find($nom)
    {
        $query = 'MATCH (b:BassinVersant {nomBassinVersant: $nom}) RETURN b LIMIT 1';
        return $this->client->run($query, ['nom' => $nom])->first();
    }

    public function update($nom, $newNom)
    {
        $query = 'MATCH (b:BassinVersant {nomBassinVersant: $nom}) SET b.nomBassinVersant = $newNom RETURN b';
        $this->client->run($query, ['nom' => $nom, 'newNom' => $newNom]);
    }

    public function delete($nom)
    {
        $query = 'MATCH (b:BassinVersant {nomBassinVersant: $nom}) DETACH DELETE b';
        $this->client->run($query, ['nom' => $nom]);
    }
}
