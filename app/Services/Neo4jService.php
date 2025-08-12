<?php


namespace App\Services;

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Databags\Statement;

class Neo4jService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->withDriver('default', 'neo4j://neo4j:Admin1234@localhost:7687')
            ->build();
    }

    public function getClient()
    {
        return $this->client;
    }

    // 🔍 Obtenir tous les cours d’eau avec leurs relations
    public function getAllCoursEau()
    {
        $query = <<<CYPHER
MATCH (c:CoursEau)
OPTIONAL MATCH (c)-[:aPourBassinVersant]->(b:BassinVersant)
OPTIONAL MATCH (c)-[:traverse]->(l:LocaliteGeographique)
OPTIONAL MATCH (c)-[:aPourAffluent]->(a:Affluent)
RETURN c AS c, b AS b, collect(l) AS l, collect(a) AS a

CYPHER;

        return $this->client->run($query)->toArray();
    }

    // ✅ Créer un cours d'eau
  public function createCoursEau(array $data)
{
    $query = <<<CYPHER
CREATE (c:CoursEau {
    nomCoursEau: \$nom,
    longueurCoursEau: \$longueur,
    debitMoyenCoursEau: \$debit,
    regimehydrologique: \$regime,
    nomSource: \$source,
    nomVersement: \$versement
})
WITH c
OPTIONAL MATCH (l:LocaliteGeographique {nomLocGeo: \$localite})
OPTIONAL MATCH (b:BassinVersant {nomBassinVersant: \$bassin})
FOREACH (_ IN CASE WHEN l IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:traverse]->(l))
FOREACH (_ IN CASE WHEN b IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:aPourBassinVersant]->(b))
RETURN c
CYPHER;

    return $this->client->run($query, [
        'nom' => $data['nomCoursEau'],
        'longueur' => floatval($data['longueurCoursEau']),
        'debit' => floatval($data['debitMoyenCoursEau']),
        'regime' => $data['regimehydrologique'],
        'source' => $data['nomSource'],
        'versement' => $data['nomVersement'],
        'localite' => $data['localite_id'] ?? null,
        'bassin' => $data['bassin_id'] ?? null
    ]);
}



    // 🔄 Mettre à jour un cours d’eau
   public function updateCoursEau($id, array $data)
{
    $query = <<<CYPHER
MATCH (c:CoursEau {nomCoursEau: \$id})
SET c.longueurCoursEau = \$longueur,
    c.debitMoyenCoursEau = \$debit,
    c.regimehydrologique = \$regime,
    c.nomSource = \$source,
    c.nomVersement = \$versement
WITH c
OPTIONAL MATCH (c)-[r1:traverse]->()
DELETE r1
WITH c
OPTIONAL MATCH (c)-[r2:aPourBassinVersant]->()
DELETE r2
WITH c
OPTIONAL MATCH (l:LocaliteGeographique {nomLocGeo: \$localite})
OPTIONAL MATCH (b:BassinVersant {nomBassinVersant: \$bassin})
FOREACH (_ IN CASE WHEN l IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:traverse]->(l))
FOREACH (_ IN CASE WHEN b IS NOT NULL THEN [1] ELSE [] END | MERGE (c)-[:aPourBassinVersant]->(b))
RETURN c
CYPHER;

    return $this->client->run($query, [
        'id' => $id,
        'longueur' => $data['longueurCoursEau'],
        'debit' => $data['debitMoyenCoursEau'],
        'regime' => $data['regimehydrologique'],
        'source' => $data['nomSource'] ?? '',
        'versement' => $data['nomVersement'] ?? '',
        'localite' => $data['localite_id'] ?? null,
        'bassin' => $data['bassin_id'] ?? null,
    ]);
}



    // 🗑️ Supprimer un cours d’eau
   public function deleteCoursEau($id)
{
    $query = <<<CYPHER
MATCH (c:CoursEau {nomCoursEau: \$id})
DETACH DELETE c
CYPHER;

    return $this->client->run($query, ['id' => $id]);
}

public function findCoursEauByName($name)
{
    $query = <<<CYPHER
MATCH (c:CoursEau {nomCoursEau: \$name})
OPTIONAL MATCH (c)-[:traverse]->(l:LocaliteGeographique)
OPTIONAL MATCH (c)-[:aPourBassinVersant]->(b:BassinVersant)
OPTIONAL MATCH (c)-[:aPourSource]->(s:Source)
RETURN c, l, b, s
CYPHER;

    return $this->client->run($query, ['name' => $name])->first();
}

    // 🔍 Obtenir toutes les localités géographiques
public function getAllLocalites()
{
    $query = "MATCH (l:LocaliteGeographique) RETURN l";
    return $this->client->run($query)->toArray();
}

// 🔍 Obtenir tous les bassins versants


public function getAllAffluents()
{
    $query = "MATCH (a:Affluent) RETURN a";
    return $this->client->run($query)->toArray();
}

public function createAffluent(array $data)
{
    $query = <<<CYPHER
CREATE (a:Affluent {
    nomAffluent: \$nom,
    debitAffluent: \$debit
})
RETURN a
CYPHER;

    return $this->client->run($query, [
        'nom' => $data['nomAffluent'],
        'debit' => $data['debitAffluent'] ?? 0,
    ]);
}


public function findAffluentByName($nom)
{
    $query = "MATCH (a:Affluent {nomAffluent: \$nom}) RETURN a LIMIT 1";
    return $this->client->run($query, ['nom' => $nom])->first();
}

public function updateAffluent($nom, array $data)
{
    $query = <<<CYPHER
MATCH (a:Affluent {nomAffluent: \$nom})
SET a.longueurAffluent = \$longueur,
    a.debitAffluent = \$debit
RETURN a
CYPHER;

    return $this->client->run($query, [
        'nom' => $nom,
        'longueur' => $data['longueurAffluent'] ?? 0,
        'debit' => $data['debitAffluent'] ?? 0,
    ]);
}


public function deleteAffluent($nom)
{
    $query = "MATCH (a:Affluent {nomAffluent: \$nom}) DETACH DELETE a";
    return $this->client->run($query, ['nom' => $nom]);
}
public function getAllLocalitesGroupedByRegion()
{
    $query = <<<CYPHER
MATCH (v:Ville)-[:estDansRegion]->(r:LocaliteGeographique)
WHERE v.nomVille IS NOT NULL AND r.nomLocGeo IS NOT NULL
RETURN r.nomLocGeo AS region, v.nomVille AS ville
ORDER BY r.nomLocGeo, v.nomVille
CYPHER;

    return $this->client->run($query)->toArray();
}
public function getAllRegions()
{
    $query = <<<CYPHER
MATCH (r:LocaliteGeographique)
WHERE r.nomLocGeo IS NOT NULL AND r.nomVille IS NULL
RETURN DISTINCT r.nomLocGeo AS nom
ORDER BY nom
CYPHER;

    return $this->client->run($query)->toArray();
}



// 🔍 Récupérer une ville
public function findVille($nom)
{
    $query = <<<CYPHER
MATCH (v:LocaliteGeographique {nomVille: \$nom})
OPTIONAL MATCH (v)-[:estDansRegion]->(r:LocaliteGeographique)
RETURN v, r
CYPHER;

    return $this->client->run($query, ['nom' => $nom])->first();
}

// ➕ Ajouter une ville
public function createVille(array $data)
{
    $query = <<<CYPHER
MERGE (v:Ville {nomVille: \$ville})
WITH v
MATCH (r:LocaliteGeographique {nomLocGeo: \$region})
MERGE (v)-[:estDansRegion]->(r)
RETURN v
CYPHER;

    return $this->client->run($query, [
        'ville' => $data['nomVille'],
        'region' => $data['nomLocGeo'],
    ]);
}


// 🔄 Modifier une ville
public function updateVille($nomInitial, array $data)
{
    $query = <<<CYPHER
MATCH (v:Ville {nomVille: \$oldName})
SET v.nomVille = \$newName
WITH v
OPTIONAL MATCH (v)-[rel:estDansRegion]->(:LocaliteGeographique)
DELETE rel
WITH v
MERGE (r:LocaliteGeographique {nomLocGeo: \$region})
MERGE (v)-[:estDansRegion]->(r)
RETURN v
CYPHER;

    return $this->client->run($query, [
        'oldName' => $nomInitial,
        'newName' => $data['nomVille'],
        'region' => $data['nomLocGeo'],
    ]);
}

public function deleteVille($nom)
{
    $query = <<<CYPHER
MATCH (v:Ville {nomVille: \$nom})
DETACH DELETE v
CYPHER;

    return $this->client->run($query, ['nom' => $nom]);
}

// Liste tous les bassins
public function getAllBassins()
{
    $query = <<<CYPHER
MATCH (b:BassinVersant)
RETURN b
ORDER BY b.nomBassinVersant
CYPHER;

    return $this->client->run($query)->toArray();
}


public function createBassin(array $data)
{
    $query = <<<CYPHER
CREATE (b:BassinVersant {
    nomBassinVersant: \$nom,
    superficieBassinVersant: \$superficie,
    partSurfaceNationaleBassinVersant: \$part,
    uri: \$uri
})
RETURN b
CYPHER;

    return $this->client->run($query, [
        'nom' => $data['nomBassinVersant'],
        'superficie' => $data['superficieBassinVersant'],
        'part' => $data['partSurfaceNationaleBassinVersant'],
        'uri' => $data['uri'] ?? 'http://example.org/ontology#' . $data['nomBassinVersant'],
    ]);
}

public function findBassinByName($nom)
{
    $query = "MATCH (b:BassinVersant {nomBassinVersant: \$nom}) RETURN b";
    return $this->client->run($query, ['nom' => $nom])->first();
}

public function updateBassin($nom, array $data)
{
    $query = <<<CYPHER
MATCH (b:BassinVersant {nomBassinVersant: \$nom})
SET b.superficieBassinVersant = \$superficie,
    b.partSurfaceNationaleBassinVersant = \$part
RETURN b
CYPHER;

    return $this->client->run($query, [
        'nom' => $nom,
        'superficie' => $data['superficieBassinVersant'],
        'part' => $data['partSurfaceNationaleBassinVersant'],
    ]);
}

public function deleteBassin($nom)
{
    $query = "MATCH (b:BassinVersant {nomBassinVersant: \$nom}) DETACH DELETE b";
    return $this->client->run($query, ['nom' => $nom]);
}

}
