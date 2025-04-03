<?php
require_once __DIR__ . '/vendor/autoload.php';

// Récupérer l'URI
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Définir le chemin de base du projet
$projectBase = 'FootAPI/gestion_api_front';

// Retirer le chemin de base de l'URI
if (strpos($uri, $projectBase) === 0) {
    $uri = substr($uri, strlen($projectBase));
    $uri = trim($uri, '/');
}

// Inclure le fichier correspondant à l'URI
$file = __DIR__ . '/' . $uri;
if (file_exists($file) && is_file($file)) {
    require $file;
} else {
    http_response_code(404);
    echo "Oupss on dirait que vous vous êtes perdu en chemin...";
}