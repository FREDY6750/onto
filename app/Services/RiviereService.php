<?php
namespace App\Services;

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Types\Node;

class RiviereService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->withDriver('default', 'neo4j://neo4j:Admin1234@localhost:7687')
            ->build();
    }

    /**
     * Convertit un Node Neo4j en array associatif PHP.
     */
    /**
 * Convertit un Node Neo4j en array associatif PHP.
 */
private static function nodeToArray(Node $node): array
{
    // getProperties() → CypherMap → toArray()
    return $node->getProperties()->toArray();
}


    /**
     * Liste des rivières sous forme de tableaux associatifs.
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        $result = $this->client->run('MATCH (r:Riviere) RETURN r ORDER BY r.nomCoursEau');
        $rows = $result->toArray();

        return array_map(function ($row) {
            /** @var Node $node */
            $node = $row->get('r');
            return self::nodeToArray($node);
        }, $rows);
    }

    /**
     * Trouve une rivière par son nom et retourne un array ou null.
     */
    public function find(string $nomCoursEau): ?array
    {
        $res = $this->client->run(
            'MATCH (r:Riviere {nomCoursEau: $nom}) RETURN r LIMIT 1',
            ['nom' => $nomCoursEau]
        );

        if ($res->count() === 0) {
            return null;
        }

        /** @var Node $node */
        $node = $res->first()->get('r');
        return self::nodeToArray($node);
    }

    /**
     * Création.
     */
    public function create(array $data): void
    {
        $cypher = 'CREATE (r:Riviere {
            nomCoursEau: $nomCoursEau,
            typeCoursEau: $typeCoursEau,
            longueurCoursEau: $longueurCoursEau,
            debitMoyenCoursEau: $debitMoyenCoursEau,
            nomSource: $nomSource,
            nomVersement: $nomVersement
        }) RETURN r';

        $params = [
            'nomCoursEau'       => $data['nomCoursEau'],
            'typeCoursEau'      => $data['typeCoursEau']      ?? null,
            'longueurCoursEau'  => isset($data['longueurCoursEau']) ? (float)$data['longueurCoursEau'] : null,
            'debitMoyenCoursEau'=> isset($data['debitMoyenCoursEau']) ? (float)$data['debitMoyenCoursEau'] : null,
            'nomSource'         => $data['nomSource']         ?? null,
            'nomVersement'      => $data['nomVersement']      ?? null,
        ];

        $this->client->run($cypher, $params);
    }

    /**
     * Mise à jour. $ancienNom = identifiant actuel (clé d’URL), $data peut contenir un nouveau nom.
     */
    public function update(string $ancienNom, array $data): void
    {
        $cypher = 'MATCH (r:Riviere {nomCoursEau: $ancienNom})
                   SET r.nomCoursEau = $nomCoursEau,
                       r.typeCoursEau = $typeCoursEau,
                       r.longueurCoursEau = $longueurCoursEau,
                       r.debitMoyenCoursEau = $debitMoyenCoursEau,
                       r.nomSource = $nomSource,
                       r.nomVersement = $nomVersement
                   RETURN r';

        $params = [
            'ancienNom'         => $ancienNom,
            'nomCoursEau'       => $data['nomCoursEau'],
            'typeCoursEau'      => $data['typeCoursEau']      ?? null,
            'longueurCoursEau'  => isset($data['longueurCoursEau']) ? (float)$data['longueurCoursEau'] : null,
            'debitMoyenCoursEau'=> isset($data['debitMoyenCoursEau']) ? (float)$data['debitMoyenCoursEau'] : null,
            'nomSource'         => $data['nomSource']         ?? null,
            'nomVersement'      => $data['nomVersement']      ?? null,
        ];

        $this->client->run($cypher, $params);
    }

    /**
     * Suppression (avec ses relations).
     */
    public function delete(string $nomCoursEau): void
    {
        $this->client->run(
            'MATCH (r:Riviere {nomCoursEau: $nom}) DETACH DELETE r',
            ['nom' => $nomCoursEau]
        );
    }
}
