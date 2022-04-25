<?php 

// Constantes
const FILENAME = 'articles.json';


//////////////// FONCTIONS ////////////////

/**
 * Récupère des données stockées dans un fichier JSON
 * @param string $filepath - Le chemin vers le fichier qu'on souhaite lire
 * @return mixed - Les données stockées dans le fichier JSON désérialisées
 */
function loadJSON(string $filepath)
{
    // Si le fichier spécifié n'existe pas on retourne false
    if (!file_exists($filepath)) {
        return false;
    }

    // On récupère le contenu du fichier
    $jsonData = file_get_contents($filepath);

    // On retourne les données désérialisées
    return json_decode($jsonData, true);
}

/**
 * Ecrit des données dans un fichier au format JSON
 * @param string $filepath - Le chemin vers le fichier qu'on souhaite lire
 * @param $data - Les données qu'on souhaite enregistrer dans le fichier JSON
 * @return void
 */
function saveJSON(string $filepath, $data)
{
    // On sérialise les données en JSON
    $jsonData = json_encode($data);

    // On écrit le JSON dans le fichier
    file_put_contents($filepath, $jsonData);
}

/**
 * Récupère l'intégralité des articles ou un tableau vide
 * @return array - Le tableau d'articles
 */
function getAllArticles(): array
{
    // On récupère le contenu de fichier JSON
    $articles = loadJSON(FILENAME);

    // Si on ne récupère rien (fichier inexistant ou vide)
    if ($articles == false) {
        return [];
    }

    // Sinon on retourne directement notre tableau d'articles
    return $articles;
}

/**
 * Ajoute un article
 * @param string $title Le titre de l'article
 * @param string $abstract Le résumé de l'article
 * @param string $content Le contenu de l'article
 * @param string $title Le nom du fichier image de l'article
 * @return void
 */
function addArticle(string $title, string $abstract, string $content, string $image)
{
    // On commence par récupérer tous les articles
    $articles = getAllArticles();

    // Création de la date de création de l'article (date du jour)
    $today = new DateTimeImmutable();

    // On regroupe les informations du nouvel article dans un tableau associatif
    $article = [
        'id' => sha1(uniqid(rand(), true)),
        'title' => $title,
        'abstract' => $abstract,
        'content' => $content,
        'image' => $image,
        'createdAt' => $today->format('Y-m-d')
    ];

    // On ajoute le nouvel article au tableau d'articles
    $articles[] = $article;

    // On enregistre les articles à nouveau dans le fichier JSON
    saveJSON(FILENAME, $articles);
}

/**
 * Récupère UN article à partir de son identifiant
 * @param string $idArticle - L'identifiant de l'article à récupérer
 * @return null|array - null si l'id n'existe pas, sinon retourne l'article
 */
function getOneArticle(string $idArticle): ?array
{
    $articles = getAllArticles();
    foreach ($articles as $article) {
        if ($article['id'] == $idArticle) {
            return $article;
        }
    }
    return null;
}

/**
 * Modifie un article
 * @param string $title Le titre de l'article
 * @param string $abstract Le résumé de l'article
 * @param string $content Le contenu de l'article
 * @param string $title Le nom du fichier image de l'article
 * @return void
 */
function editArticle(string $title, string $abstract, string $content, string $image, string $idArticle)
{
    // On récupère tous les articles
    $articles = getAllArticles();

    // On parcours le tableau d'articles à la recherche de l'article à modifier
    foreach ($articles as $index => $article) {

        // Si l'id de l'article courant est le bon...
        if ($article['id'] == $idArticle) {

            // On modifie la case du tableau contenant l'article à modifier
            $articles[$index]['title'] = $title;
            $articles[$index]['abstract'] = $abstract;
            $articles[$index]['content'] = $content;
            $articles[$index]['image'] = $image;
            break;
        }
    }

    // On enregistre les articles à nouveau dans le fichier JSON
    saveJSON(FILENAME, $articles);
}

/**
 * Supprime un article à partir de son identifiant
 * @param string $idArticle - L'identifiant de l'article à supprimer
 */
function deleteArticle(string $idArticle)
{
    // On récupère tous les articles
    $articles = getAllArticles();

    // Initialisation d'une variable qui stockera l'indice de l'élément à supprimer
    $indexToDelete = null;

    // On parcours le tableau d'articles à la recherche de l'article à supprimer
    foreach ($articles as $index => $article) {
        
        // Si l'id de l'article courant est le bon...
        if ($article['id'] == $idArticle) {

            // Je stocke l'indice de l'élément à supprimer
            $indexToDelete = $index;
            break;
        }
    }

    // Si j'ai bien trouvé l'élémentà supprimer...
    if (!is_null($indexToDelete)) {

        // ... je le supprime !
        array_splice($articles, $indexToDelete, 1);
    }
    
    // On enregistre les articles à nouveau dans le fichier JSON
    saveJSON(FILENAME, $articles);
}