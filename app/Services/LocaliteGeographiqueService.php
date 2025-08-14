<?php
namespace App\Services;

use Laudis\Neo4j\ClientBuilder;


class LocaliteGeographiqueService
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
        return $this->client->run('MATCH (l:LocaliteGeographique) RETURN l')->toArray();
    }

    public function create(array $data)
    {
        $cypher = 'CREATE (l:LocaliteGeographique {nomLocGeo: $nom}) RETURN l';
        $this->client->run($cypher, ['nom' => $data['nomLocGeo']]);
    }

    public function find($nom)
    {
        return $this->client->run('MATCH (l:LocaliteGeographique {nomLocGeo: $nom}) RETURN l LIMIT 1', ['nom' => $nom])->first();
    }

    public function update($nom, array $data)
    {
        $cypher = 'MATCH (l:LocaliteGeographique {nomLocGeo: $nom}) SET l.nomLocGeo = $nouveau RETURN l';
        $this->client->run($cypher, ['nom' => $nom, 'nouveau' => $data['nomLocGeo']]);
    }

    public function delete($nom)
    {
        $this->client->run('MATCH (l:LocaliteGeographique {nomLocGeo: $nom}) DETACH DELETE l', ['nom' => $nom]);
    }
}
