<?php
require_once __DIR__ . '/vendor/autoload.php';

// Récupérer l'URI sans le chemin de base
$uri = str_replace('/FootAPI/gestion_api_front/', '', $_SERVER['REQUEST_URI']);
$uri = trim($uri, '/');

// Mapping des routes propres vers les fichiers correspondants
$routes = [
    'accueil' => 'app/vues/Accueil/accueil.php',
    'rencontres' => 'app/vues/Rencontres/liste_rencontres.php',
    'joueurs' => 'app/vues/Joueurs/liste_joueurs.php',
    'statistiques' => 'app/vues/Statistiques/stats.php',
    'logout' => 'app/vues/Authentification/logout.php',
    'login' => 'app/vues/Authentification/login.php',
];

// Vérifier si la route existe et inclure le bon fichier
if (array_key_exists($uri, $routes) && file_exists($routes[$uri])) {
    require $routes[$uri];
} else {
    http_response_code(404);
    echo "Oupss, cette page n'existe pas...";
}
?>
