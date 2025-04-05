<?php
require_once __DIR__ . '/vendor/autoload.php';

// Extraire uniquement la partie avant le ?
$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
$uri = str_replace('/FootAPI/gestion_api_front/', '', $uri);
$uri = trim($uri, '/');


// Mapping des routes propres vers les fichiers correspondants
$routes = [
    'accueil' => 'app/vues/Accueil/accueil.php',
    'rencontres' => 'app/vues/Rencontres/liste_rencontres.php',
    'joueurs' => 'app/vues/Joueurs/liste_joueurs.php',
    'form_joueur' => 'app/vues/Joueurs/form_joueur.php',
    'modifier_joueur' => 'app/vues/Joueurs/modifier_joueur.php',
    'statistiques' => 'app/vues/Statistiques/stats.php',
    'logout' => 'app/vues/Authentification/logout.php',
    'login' => 'app/vues/Authentification/login.php',
    'ajouter_rencontre' => 'app/vues/Rencontres/ajouter_rencontre.php',
    'ajouter_resultat' => 'app/vues/Equipes/ajouter_resultat.php',
    'modifier_rencontre' => 'app/vues/Rencontres/modifier_rencontre.php',
    'selectionner' => 'app/vues/Rencontres/feuille_rencontres.php',
];

// Vérifier si la route existe et inclure le bon fichier
if (array_key_exists($uri, $routes) && file_exists($routes[$uri])) {
    require $routes[$uri];
} else {
    http_response_code(404);
    echo "Oupss, cette page n'existe pas...";
}
?>
