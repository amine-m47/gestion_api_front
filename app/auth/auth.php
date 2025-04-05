<?php
session_start();
require_once './jwt_utils.php';

function verifierUtilisateurConnecte() {
    $current_url = $_SERVER['REQUEST_URI'];
    $allowed_urls = [
        '/FootAPI/gestion_api_front/accueil',
        '/FootAPI/gestion_api_front/connexion'
    ];

    if (!isset($_SESSION['token']) || !is_jwt_valid($_SESSION['token'], 'cle_secrete')) {
        if (!in_array($current_url, $allowed_urls)) {
            // Si l'utilisateur n'est pas connecté ou si le token n'est pas valide, rediriger vers l'accueil
            header("Location: /FootAPI/gestion_api_front/accueil");
            exit;
        }
        return false;
    }
    return true;
}