<?php

namespace App\Services;

use Laudis\Neo4j\ClientBuilder;

class SousBassinRegionalService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->withDriver('default', 'neo4j://neo4j:Admin1234@localhost:7687')
            ->build();
    }

    /** Liste (pour index) */
    public function all(): array
    {
        $cypher = <<<'CYPHER'
MATCH (s:SousBassinVersantRegional)
RETURN {
  nomBassinVersant: s.nomBassinVersant
} AS sbv
ORDER BY sbv.nomBassinVersant
CYPHER;

        $rows = $this->client->run($cypher)->toArray();
        return array_map(fn($row) => $row->get('sbv'), $rows);
    }

    /** Détail par nom (pour edit) */
    public function find(string $nomBassinVersant): ?array
    {
        $cypher = <<<'CYPHER'
MATCH (s:SousBassinVersantRegional {nomBassinVersant: $nomBassinVersant})
RETURN {
  nomBassinVersant: s.nomBassinVersant
} AS sbv
LIMIT 1
CYPHER;

        $res = $this->client->run($cypher, ['nomBassinVersant' => $nomBassinVersant])->toArray();
        if (!$res) return null;
        return $res[0]->get('sbv');
    }

    /** Création / upsert */
    public function create(array $data): void
    {
        $cypher = <<<'CYPHER'
MERGE (s:SousBassinVersantRegional {nomBassinVersant: $nomBassinVersant})
ON MATCH SET s.nomBassinVersant = $nomBassinVersant
CYPHER;

        $this->client->run($cypher, [
            'nomBassinVersant' => $data['nomBassinVersant'],
        ]);
    }

    /** Mise à jour */
    public function update(string $nomBassinVersant, array $data): void
    {
        $cypher = <<<'CYPHER'
MATCH (s:SousBassinVersantRegional {nomBassinVersant: $nomBassinVersant})
SET s.nomBassinVersant = coalesce($newNomSousBassinVersant, s.nomBassinVersant)
CYPHER;

        $this->client->run($cypher, [
            'nomBassinVersant'   => $nomBassinVersant,
            'newNomSousBassinVersant'=> $data['nomBassinVersant'] ?? null,
        ]);
    }

    /** Suppression */
    public function delete(string $nomBassinVersant): void
    {
        $this->client->run(
            'MATCH (s:SousBassinVersantRegional {nomBassinVersant: $nom}) DETACH DELETE s',
            ['nom' => $nomBassinVersant]
        );
    }
}
