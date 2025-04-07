<?php
session_start();

// Store the referrer URL
$referrer = $_SERVER['HTTP_REFERER'] ?? 'app/vues/Accueil/accueil.php';

unset($_SESSION['token']);

// Destroy the session
session_unset();
session_destroy();

// Redirect to the referrer URL
header("Location: $referrer");
exit;
?>