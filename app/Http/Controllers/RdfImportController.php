<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EasyRdf\Graph;
use Laudis\Neo4j\ClientBuilder;

class RdfImportController extends Controller
{
    public function importRdf()
    {
        // Chargement du fichier RDF
        $graph = new Graph();
        $graph->parseFile(public_path('ontologie_cours_eau.rdf'), 'rdfxml');

        foreach ($graph->resources() as $resource) {
            $subject = $resource->getUri();

            foreach ($resource->propertyUris() as $propertyUri) {
                $values = $resource->all($propertyUri);

                foreach ($values as $value) {
                    $object = $value instanceof \EasyRdf\Resource ? $value->getUri() : (string)$value;

                    // Affichage pour vérification
                    echo "<b>Triplet:</b><br>sujet: $subject<br>prédicat: $propertyUri<br>objet: $object<br><hr>";

                    // Envoi dans Neo4j
                    $this->sendToNeo4j($subject, $propertyUri, $object);
                }
            }
        }

        return "Import RDF terminé.";
    }

    protected function sendToNeo4j($subject, $predicate, $object)
    {
        $client = ClientBuilder::create()
            ->withDriver('bolt', 'bolt://neo4j:password@localhost:7687') // ⛔ Remplace 'password' par le mot de passe réel
            ->build();

        $cypher = '
            MERGE (s:Resource {uri: $subject})
            MERGE (o:Resource {uri: $object})
            MERGE (s)-[:' . $this->sanitizeRelation($predicate) . ']->(o)
        ';

        $client->run($cypher, [
            'subject' => $subject,
            'object' => $object
        ]);
    }

    protected function sanitizeRelation($uri)
    {
        // Transforme l'URI en nom de relation Cypher valide
        $fragment = parse_url($uri, PHP_URL_FRAGMENT);

        if (!$fragment) {
            $fragment = basename($uri);
        }

        return strtoupper(preg_replace('/[^a-zA-Z0-9]/', '_', $fragment));
    }
}
