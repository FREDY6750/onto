<?php
namespace App\Services;

use Laudis\Neo4j\ClientBuilder;

class CoursEauService
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
        $query = <<<CYPHER
MATCH (c:CoursEau)
OPTIONAL MATCH (c)-[:aPourBassinVersant]->(b:BassinVersant)
OPTIONAL MATCH (c)-[:traverse]->(l:LocaliteGeographique)
OPTIONAL MATCH (c)-[:aPourAffluent]->(a:Affluent)
OPTIONAL MATCH (c)-[:seJetteDans]->(f:Fleuve)
RETURN c AS cours, b AS bassin, l AS localite, a AS affluent, f AS fleuve
CYPHER;

        return $this->client->run($query)->toArray();
    }

    public function create(array $data)
    {
        $query = <<<CYPHER
MERGE (c:CoursEau {
    nomCoursEau: \$nom,
    typeCoursEau: \$type,
    longueurCoursEau: \$longueur,
    debitMoyenCoursEau: \$debit,
    nomSource: \$source,
    regimehydrologique: \$regime
})
WITH c
OPTIONAL MATCH (b:BassinVersant {nomBassinVersant: \$bassin})
OPTIONAL MATCH (l:LocaliteGeographique {nomLocGeo: \$localite})
OPTIONAL MATCH (a:Affluent {nomAffluent: \$affluent})
OPTIONAL MATCH (f:Fleuve {nomFleuve: \$fleuve})
FOREACH (_ IN CASE WHEN b IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:aPourBassinVersant]->(b))
FOREACH (_ IN CASE WHEN l IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:traverse]->(l))
FOREACH (_ IN CASE WHEN a IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:aPourAffluent]->(a))
FOREACH (_ IN CASE WHEN f IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:seJetteDans]->(f))
RETURN c
CYPHER;

        $this->client->run($query, [
            'nom' => $data['nomCoursEau'],
            'type' => $data['typeCoursEau'] ?? '',
            'longueur' => floatval($data['longueurCoursEau'] ?? 0),
            'debit' => floatval($data['debitMoyenCoursEau'] ?? 0),
            'source' => $data['nomSource'] ?? '',
            'regime' => $data['regimehydrologique'] ?? '',
            'bassin' => $data['bassin_id'] ?? null,
            'localite' => $data['localite_id'] ?? null,
            'affluent' => $data['affluent_id'] ?? null,
            'fleuve' => $data['fleuve_id'] ?? null,
        ]);
    }

    public function find($nom)
    {
        $query = <<<CYPHER
MATCH (c:CoursEau {nomCoursEau: \$nom})
OPTIONAL MATCH (c)-[:aPourBassinVersant]->(b:BassinVersant)
OPTIONAL MATCH (c)-[:traverse]->(l:LocaliteGeographique)
OPTIONAL MATCH (c)-[:aPourAffluent]->(a:Affluent)
OPTIONAL MATCH (c)-[:seJetteDans]->(f:Fleuve)
RETURN c AS cours, b AS bassin, l AS localite, a AS affluent, f AS fleuve
LIMIT 1
CYPHER;

        return $this->client->run($query, ['nom' => $nom])->first();
    }

    public function update($nom, array $data)
    {
        $query = <<<CYPHER
MATCH (c:CoursEau {nomCoursEau: \$nom})
SET c.nomCoursEau = \$newNom,
    c.typeCoursEau = \$type,
    c.longueurCoursEau = \$longueur,
    c.debitMoyenCoursEau = \$debit,
    c.nomSource = \$source,
    c.regimehydrologique = \$regime
WITH c
OPTIONAL MATCH (c)-[r1:aPourBassinVersant]->()
DELETE r1
WITH c
OPTIONAL MATCH (c)-[r2:traverse]->()
DELETE r2
WITH c
OPTIONAL MATCH (c)-[r3:aPourAffluent]->()
DELETE r3
WITH c
OPTIONAL MATCH (c)-[r4:seJetteDans]->()
DELETE r4
WITH c
OPTIONAL MATCH (b:BassinVersant {nomBassinVersant: \$bassin})
OPTIONAL MATCH (l:LocaliteGeographique {nomLocGeo: \$localite})
OPTIONAL MATCH (a:Affluent {nomAffluent: \$affluent})
OPTIONAL MATCH (f:Fleuve {nomFleuve: \$fleuve})
FOREACH (_ IN CASE WHEN b IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:aPourBassinVersant]->(b))
FOREACH (_ IN CASE WHEN l IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:traverse]->(l))
FOREACH (_ IN CASE WHEN a IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:aPourAffluent]->(a))
FOREACH (_ IN CASE WHEN f IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:seJetteDans]->(f))
RETURN c
CYPHER;

        $this->client->run($query, [
            'nom' => $nom,
            'newNom' => $data['nomCoursEau'],
            'type' => $data['typeCoursEau'] ?? '',
            'longueur' => floatval($data['longueurCoursEau'] ?? 0),
            'debit' => floatval($data['debitMoyenCoursEau'] ?? 0),
            'source' => $data['nomSource'] ?? '',
            'regime' => $data['regimehydrologique'] ?? '',
            'bassin' => $data['bassin_id'] ?? null,
            'localite' => $data['localite_id'] ?? null,
            'affluent' => $data['affluent_id'] ?? null,
            'fleuve' => $data['fleuve_id'] ?? null,
        ]);
    }

    public function delete($nom)
    {
        $this->client->run(
            'MATCH (c:CoursEau {nomCoursEau: $nom}) DETACH DELETE c',
            ['nom' => $nom]
        );
    }

    public function getAllLocalites()
    {
        return $this->client->run("MATCH (l:LocaliteGeographique) RETURN l")->toArray();
    }

    public function getAllBassins()
    {
        return $this->client->run("MATCH (b:BassinVersant) RETURN b")->toArray();
    }
}
