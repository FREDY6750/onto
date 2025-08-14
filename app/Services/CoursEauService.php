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

    /**
     * Récupère tous les cours d’eau
     */
    public function all()
    {
        $query = <<<CYPHER
MATCH (c:CoursEau)
RETURN c
ORDER BY c.nomCoursEau
CYPHER;

        return $this->client->run($query)->toArray();
    }

    /**
     * Crée un nouveau cours d’eau
     */
    public function create(array $data)
    {
        $query = <<<CYPHER
MERGE (c:CoursEau {nomCoursEau: \$nomCoursEau})
SET c.typeCoursEau       = \$typeCoursEau,
    c.longueurCoursEau   = \$longueurCoursEau,
    c.debitMoyenCoursEau = \$debitMoyenCoursEau,
    c.nomSource          = \$nomSource,
    c.nomVersement       = \$nomVersement
RETURN c
CYPHER;

        $this->client->run($query, [
            'nomCoursEau'        => $data['nomCoursEau'],
            'typeCoursEau'       => $data['typeCoursEau'] ?? '',
            'longueurCoursEau'   => floatval($data['longueurCoursEau'] ?? 0),
            'debitMoyenCoursEau' => floatval($data['debitMoyenCoursEau'] ?? 0),
            'nomSource'          => $data['nomSource'] ?? '',
            'nomVersement'       => $data['nomVersement'] ?? ''
        ]);
    }

    /**
     * Trouve un cours d’eau par son nom
     */
    public function find($nomCoursEau)
    {
        $query = <<<CYPHER
MATCH (c:CoursEau {nomCoursEau: \$nomCoursEau})
RETURN c
LIMIT 1
CYPHER;

        return $this->client->run($query, [
            'nomCoursEau' => $nomCoursEau
        ])->first();
    }

    /**
     * Met à jour un cours d’eau
     */
    public function update($nomCoursEau, array $data)
    {
        $query = <<<CYPHER
MATCH (c:CoursEau {nomCoursEau: \$nomCoursEau})
SET c.nomCoursEau        = \$newNomCoursEau,
    c.typeCoursEau       = \$typeCoursEau,
    c.longueurCoursEau   = \$longueurCoursEau,
    c.debitMoyenCoursEau = \$debitMoyenCoursEau,
    c.nomSource          = \$nomSource,
    c.nomVersement       = \$nomVersement
RETURN c
CYPHER;

        $this->client->run($query, [
            'nomCoursEau'        => $nomCoursEau,
            'newNomCoursEau'     => $data['nomCoursEau'],
            'typeCoursEau'       => $data['typeCoursEau'] ?? '',
            'longueurCoursEau'   => floatval($data['longueurCoursEau'] ?? 0),
            'debitMoyenCoursEau' => floatval($data['debitMoyenCoursEau'] ?? 0),
            'nomSource'          => $data['nomSource'] ?? '',
            'nomVersement'       => $data['nomVersement'] ?? ''
        ]);
    }

    /**
     * Supprime un cours d’eau
     */
    public function delete($nomCoursEau)
    {
        $query = <<<CYPHER
MATCH (c:CoursEau {nomCoursEau: \$nomCoursEau})
DETACH DELETE c
CYPHER;

        $this->client->run($query, [
            'nomCoursEau' => $nomCoursEau
        ]);
    }
}
