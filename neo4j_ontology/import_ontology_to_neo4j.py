from rdflib import Graph, Namespace, URIRef, Literal, RDF
from py2neo import Graph as Neo4jGraph, Node, Relationship

# Charger le fichier RDF
rdf_graph = Graph()
rdf_graph.parse("ontologiecourseauprojet.rdf", format="xml")

# Connexion Neo4j
neo4j = Neo4jGraph("bolt://localhost:7687", auth=("neo4j", "Admin1234"))

# Namespace
NS = Namespace("http://www.semanticweb.org/hp/ontologies/2025/6/untitled-ontology-26#")

# Étape 1 : stocker les types (classes) des ressources
resource_types = {}
for s, p, o in rdf_graph.triples((None, RDF.type, None)):
    if isinstance(o, URIRef) and str(o).startswith(str(NS)):
        class_name = str(o).split("#")[-1]
        resource_types[str(s)] = class_name

# Étape 2 : création des nœuds et relations
for s, p, o in rdf_graph:
    s_uri = str(s)
    p_uri = str(p)
    o_str = str(o)

    # Déduire le label de la classe
    label = resource_types.get(s_uri, "Resource")

    # Créer ou récupérer le nœud sujet
    subject_node = neo4j.nodes.match(label, uri=s_uri).first()
    if not subject_node:
        subject_node = Node(label, uri=s_uri)
        neo4j.create(subject_node)

    # Ajouter des propriétés littérales
    if isinstance(o, Literal):
        prop_name = p_uri.split("#")[-1]
        subject_node[prop_name] = o_str
        neo4j.push(subject_node)

    # Ajouter des relations
    elif isinstance(o, URIRef):
        o_uri = str(o)
        object_label = resource_types.get(o_uri, "Resource")

        object_node = neo4j.nodes.match(object_label, uri=o_uri).first()
        if not object_node:
            object_node = Node(object_label, uri=o_uri)
            neo4j.create(object_node)

        rel_type = p_uri.split("#")[-1]
        rel = Relationship(subject_node, rel_type, object_node)
        neo4j.create(rel)
