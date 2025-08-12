<?php
namespace App\Services;

use Laudis\Neo4j\ClientBuilder;

class BassinVersantService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->withDriver('default', 'neo4j://neo4j:Admin1234@localhost:7687')
            ->build();
    }

    public function all()
    {
        $result = $this->client->run('MATCH (b:BassinVersant) RETURN b');
        return $result->toArray();
    }

    public function create(array $data)
    {
        $cypher = 'CREATE (b:BassinVersant {nomBassinVersant: $nom, superficieKm2BassinVersant: $superficie, partSurfaceNationaleBassinVersant: $part}) RETURN b';
        $this->client->run($cypher, [
            'nom' => $data['nomBassinVersant'],
            'superficie' => $data['superficieKm2BassinVersant'],
            'part' => $data['partSurfaceNationaleBassinVersant']
        ]);
    }

    public function find($nom)
    {
        $query = 'MATCH (b:BassinVersant {nomBassinVersant: $nom}) RETURN b LIMIT 1';
        return $this->client->run($query, ['nom' => $nom])->first();
    }

    public function update($nom, array $data)
    {
        $cypher = 'MATCH (b:BassinVersant {nomBassinVersant: $nom}) SET b.superficieKm2BassinVersant = $superficie, b.partSurfaceNationaleBassinVersant = $part RETURN b';
        $this->client->run($cypher, [
            'nom' => $nom,
            'superficie' => $data['superficieKm2BassinVersant'],
            'part' => $data['partSurfaceNationaleBassinVersant']
        ]);
    }

    public function delete($nom)
    {
        $cypher = 'MATCH (b:BassinVersant {nomBassinVersant: $nom}) DETACH DELETE b';
        $this->client->run($cypher, ['nom' => $nom]);
    }
}
