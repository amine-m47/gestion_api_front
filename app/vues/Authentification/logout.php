<?php
session_start();

// Store the referrer URL
$referrer = $_SERVER['HTTP_REFERER'] ?? '/FootAPI/gestion_api_front/app/vues/Accueil/accueil.php';

// Destroy the session
session_unset();
session_destroy();

// Redirect to the referrer URL
header("Location: $referrer");
exit;
?>