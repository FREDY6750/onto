<?php

namespace App\Services;

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Types\Node;
use Laudis\Neo4j\Types\CypherMap;

class SousBassinNationalService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->withDriver('default', 'neo4j://neo4j:Admin1234@localhost:7687')
            ->build();
    }

    /** Utilitaires */

    /** Convertit un CypherMap (ou Node) en array associatif PHP. */
    private static function mapToArray(CypherMap|array $m): array
    {
        if ($m instanceof CypherMap) {
            return $m->toArray();
        }
        // Déjà un array (selon drivers/versions), on le retourne tel quel
        return $m;
    }

    /** Récupère un float ou null à partir d’un array $data[$key]. */
    private static function numOrNull(array $data, string $key): ?float
    {
        if (!array_key_exists($key, $data)) return null;
        $v = $data[$key];
        return is_numeric($v) ? (float)$v : null;
    }

    /** Liste (pour index) : renvoie array<array{nomBassinVersant?:string,superficieBassinVersant?:float|null,partSurfaceNationaleBassinVersant?:float|null}> */
    public function all(): array
{
    $cypher = <<<'CYPHER'
MATCH (b:SousBassinVersantNational)
RETURN {
  nomBassinVersant: b.nomBassinVersant,
  superficieBassinVersant: b.superficieBassinVersant,
  partSurfaceNationaleBassinVersant: b.partSurfaceNationaleBassinVersant
} AS sbv
ORDER BY coalesce(sbv.nomBassinVersant, '')
CYPHER;

    $rows = $this->client->run($cypher)->toArray();

    return array_map(fn($row) => self::mapToArray($row->get('sbv')), $rows);
}


    /** Détail par nom (pour edit/show) : renvoie array|nul */
    public function find(string $nomBassinVersant): ?array
    {
        $cypher = <<<'CYPHER'
MATCH (b:SousBassinVersantNational {nomBassinVersant: $nomBassinVersant})
RETURN {
  nomBassinVersant: b.nomBassinVersant,
  superficieBassinVersant: b.superficieBassinVersant,
  partSurfaceNationaleBassinVersant: b.partSurfaceNationaleBassinVersant
} AS sbv
LIMIT 1
CYPHER;

        $res = $this->client->run($cypher, ['nomBassinVersant' => $nomBassinVersant])->toArray();
        if (!$res) {
            return null;
        }
        return self::mapToArray($res[0]->get('sbv'));
    }

    /** Création / upsert (MERGE) */
    public function create(array $data): void
    {
        $cypher = <<<'CYPHER'
MERGE (b:SousBassinVersantNational {nomBassinVersant: $nomBassinVersant})
SET b.superficieBassinVersant = $superficieBassinVersant,
    b.partSurfaceNationaleBassinVersant = $partSurfaceNationaleBassinVersant
CYPHER;

        $this->client->run($cypher, [
            'nomBassinVersant'                  => $data['nomBassinVersant'],
            'superficieBassinVersant'           => self::numOrNull($data, 'superficieBassinVersant'),
            'partSurfaceNationaleBassinVersant' => self::numOrNull($data, 'partSurfaceNationaleBassinVersant'),
        ]);
    }

    /** Mise à jour (avec possibilité de renommer) */
    public function update(string $nomBassinVersant, array $data): void
    {
        $cypher = <<<'CYPHER'
MATCH (b:SousBassinVersantNational {nomBassinVersant: $nomBassinVersant})
SET b.nomBassinVersant = coalesce($newNomBassinVersant, b.nomBassinVersant),
    b.superficieBassinVersant = $superficieBassinVersant,
    b.partSurfaceNationaleBassinVersant = $partSurfaceNationaleBassinVersant
CYPHER;

        $this->client->run($cypher, [
            'nomBassinVersant'                  => $nomBassinVersant,
            'newNomBassinVersant'               => $data['nomBassinVersant'] ?? null,
            'superficieBassinVersant'           => self::numOrNull($data, 'superficieBassinVersant'),
            'partSurfaceNationaleBassinVersant' => self::numOrNull($data, 'partSurfaceNationaleBassinVersant'),
        ]);
    }

    /** Suppression (avec détachement des relations) */
    public function delete(string $nomBassinVersant): void
    {
        $this->client->run(
            'MATCH (b:SousBassinVersantNational {nomBassinVersant: $nom}) DETACH DELETE b',
            ['nom' => $nomBassinVersant]
        );
    }
}
