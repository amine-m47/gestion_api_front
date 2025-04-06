<?php
session_start();

function verifierUtilisateurConnecte() {
    $current_url = $_SERVER['REQUEST_URI'];
    $allowed_urls = [
        '/FootAPI/gestion_api_front/accueil',
        '/FootAPI/gestion_api_front/connexion'
    ];

    if (!isset($_SESSION['token'])) {
        if (!in_array($current_url, $allowed_urls)) {
            // Si l'utilisateur n'est pas connecté, rediriger vers l'accueil
            header("Location: /FootAPI/gestion_api_front/accueil");
            exit;
        }
        return false;
    }

    $token = $_SESSION['token'];
    $api_url = 'https://footballmanagerauth.alwaysdata.net/auth';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        if (!in_array($current_url, $allowed_urls)) {
            // Si le token n'est pas valide, rediriger vers l'accueil
            header("Location: /FootAPI/gestion_api_front/accueil");
            exit;
        }
        return false;
    }

    return true;
}
?>