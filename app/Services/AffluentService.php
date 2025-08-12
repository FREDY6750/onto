<?php
namespace App\Services;

use Laudis\Neo4j\ClientBuilder;

class AffluentService
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
        $query = 'MATCH (a:Affluent)-[:seJetteDans]->(f:Fleuve) RETURN a, f';
        return $this->client->run($query)->toArray();
    }

    public function create($nomAffluent, $nomFleuve)
    {
        $cypher = <<<CYPHER
MERGE (a:Affluent {nomAffluent: \$nomA})
MERGE (f:Fleuve {nomFleuve: \$nomF})
MERGE (a)-[:seJetteDans]->(f)
CYPHER;
        $this->client->run($cypher, ['nomA' => $nomAffluent, 'nomF' => $nomFleuve]);
    }

    public function find($nomAffluent)
    {
        return $this->client->run(
            'MATCH (a:Affluent {nomAffluent: $nom})-[:seJetteDans]->(f:Fleuve) RETURN a, f LIMIT 1',
            ['nom' => $nomAffluent]
        )->first();
    }

    public function update($oldNom, $newNom, $newFleuve)
    {
        $cypher = <<<CYPHER
MATCH (a:Affluent {nomAffluent: \$oldNom})-[r:seJetteDans]->(f:Fleuve)
SET a.nomAffluent = \$newNom
DELETE r
WITH a
MERGE (f2:Fleuve {nomFleuve: \$newFleuve})
MERGE (a)-[:seJetteDans]->(f2)
CYPHER;
        $this->client->run($cypher, [
            'oldNom' => $oldNom,
            'newNom' => $newNom,
            'newFleuve' => $newFleuve
        ]);
    }

    public function delete($nom)
    {
        $this->client->run('MATCH (a:Affluent {nomAffluent: $nom}) DETACH DELETE a', ['nom' => $nom]);
    }

    public function allFleuves()
    {
        return $this->client->run('MATCH (f:Fleuve) RETURN f')->toArray();
    }
}
