<?php
// Vérifie si la constante BASE_URL n'est pas déjà définie
if (!defined('BASE_URL')) {
    define('BASE_URL', '');
}
?>
<!-- Menu pour l'utilisateur connecté -->
<nav>
    <ul>
        <li><a href="<?= BASE_URL ?>accueil">Accueil</a></li>
        <li><a href="<?= BASE_URL ?>rencontres">Rencontres</a></li>
        <li><a href="<?= BASE_URL ?>joueurs">Joueurs</a></li>
        <li><a href="<?= BASE_URL ?>statistiques">Statistiques</a></li>
        <li><a href="<?= BASE_URL ?>logout">Déconnexion</a></li>
    </ul>
</nav>
