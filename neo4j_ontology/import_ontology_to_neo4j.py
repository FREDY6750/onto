import os
import re
from rdflib import Graph as RDFGraph, Namespace, URIRef, Literal, RDF
from py2neo import Graph as Neo4jGraph, Node, Relationship

# =======================
# CONFIG
# =======================
RDF_FILENAME = "ontologiecourseauprojet.rdf"  # nom du fichier tel qu'il apparaît dans ton script
BOLT_URI = "bolt://localhost:7687"
USER = "neo4j"          # <-- utilisateur (et non pas le nom de la base)
PASSWORD = "Admin1234"
DATABASE = "groupe4"    # <-- nom de ta base de données Neo4j
NS = Namespace("http://www.semanticweb.org/hp/ontologies/2025/6/untitled-ontology-26#")

# =======================
# Utils: recherche du fichier RDF
# =======================
def find_file(filename, extra_search_dirs=None, max_depth=4):
    """
    Cherche 'filename' dans :
      - le chemin donné (si c'est déjà un chemin absolu/relatif valide)
      - le dossier du script
      - le cwd (dossier où tu exécutes le script)
      - quelques sous-dossiers jusqu'à 'max_depth' niveaux
    Renvoie le chemin absolu si trouvé, sinon lève FileNotFoundError.
    """
    candidates = []

    # 1) Tel quel
    candidates.append(filename)

    # 2) Dossier du script
    script_dir = os.path.dirname(os.path.abspath(__file__))
    candidates.append(os.path.join(script_dir, filename))

    # 3) CWD (là où tu lances python)
    cwd = os.getcwd()
    candidates.append(os.path.join(cwd, filename))

    # 4) Dossiers supplémentaires fournis
    extra_search_dirs = extra_search_dirs or []
    for d in extra_search_dirs:
        candidates.append(os.path.join(d, filename))

    # Test direct sur candidats simples
    for c in candidates:
        if os.path.isfile(c):
            return os.path.abspath(c)

    # 5) Recherche descendante limitée en profondeur depuis script_dir et cwd
    def walk_limited(root, max_depth):
        root = os.path.abspath(root)
        start_depth = root.count(os.sep)
        for current, dirs, files in os.walk(root):
            depth = current.count(os.sep) - start_depth
            if depth > max_depth:
                # on coupe la descente
                dirs[:] = []
                continue
            if filename in files:
                return os.path.join(current, filename)
        return None

    for root in {script_dir, cwd}:
        found = walk_limited(root, max_depth=max_depth)
        if found:
            return os.path.abspath(found)

    raise FileNotFoundError(
        f"Fichier RDF introuvable : '{filename}'. "
        f"Place-le à côté du script, dans le dossier courant, ou passe un chemin absolu."
    )

# =======================
# Nettoyage noms Neo4j
# =======================
def last_fragment(uri: str) -> str:
    if "#" in uri:
        return uri.rsplit("#", 1)[-1]
    return uri.rstrip("/").rsplit("/", 1)[-1]

def sanitize_label(name: str) -> str:
    name = re.sub(r'[^A-Za-z0-9_]', '_', name)
    if not re.match(r'^[A-Za-z]', name):
        name = f"L_{name}"
    return name

def sanitize_rel_type(name: str) -> str:
    name = re.sub(r'[^A-Za-z0-9_]', '_', name).upper()
    if not re.match(r'^[A-Za-z]', name):
        name = f"R_{name}"
    return name

def sanitize_prop(name: str) -> str:
    name = re.sub(r'[^A-Za-z0-9_]', '_', name)
    if not re.match(r'^[A-Za-z_]', name):
        name = f"p_{name}"
    return name

# =======================
# Charger RDF
# =======================
rdf_path = find_file(RDF_FILENAME)
print(f"[INFO] Fichier RDF trouvé : {rdf_path}")

rdf_graph = RDFGraph()
rdf_graph.parse(rdf_path, format="xml")

# =======================
# Connexion Neo4j
# =======================
neo4j = Neo4jGraph(BOLT_URI, auth=(USER, PASSWORD), name=DATABASE)

# (Optionnel mais recommandé) contrainte d'unicité pour éviter les doublons sur :Resource(uri)
neo4j.run("CREATE CONSTRAINT IF NOT EXISTS FOR (n:Resource) REQUIRE n.uri IS UNIQUE")

# =======================
# Étape 1 : types RDF des ressources
# =======================
resource_types = {}
for s, p, o in rdf_graph.triples((None, RDF.type, None)):
    if isinstance(o, URIRef) and str(o).startswith(str(NS)):
        class_name = sanitize_label(last_fragment(str(o)))
        resource_types[str(s)] = class_name

# =======================
# Étape 2 : création des nœuds et relations (idempotent)
# =======================
for s, p, o in rdf_graph:
    s_uri = str(s)
    p_uri = str(p)

    # Label déduit ou 'Resource'
    label = sanitize_label(resource_types.get(s_uri, "Resource"))

    # On donne TOUJOURS aussi le label générique :Resource pour bénéficier de la contrainte d'unicité
    subject_node = Node("Resource", label, uri=s_uri)
    neo4j.merge(subject_node, label, "uri")  # merge sur le label spécifique + clé uri

    if isinstance(o, Literal):
        prop_name = sanitize_prop(last_fragment(p_uri))
        subject_node[prop_name] = str(o)
        neo4j.push(subject_node)

    elif isinstance(o, URIRef):
        o_uri = str(o)
        object_label = sanitize_label(resource_types.get(o_uri, "Resource"))
        object_node = Node("Resource", object_label, uri=o_uri)
        neo4j.merge(object_node, object_label, "uri")

        rel_type = sanitize_rel_type(last_fragment(p_uri))
        rel = Relationship(subject_node, rel_type, object_node)
        neo4j.merge(rel)

print("✅ Import terminé sans doublons et avec la base 'groupe4'.")
