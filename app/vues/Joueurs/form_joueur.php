<?php
include __DIR__ . '/../Layouts/header.php';

$action = $_GET['action'] ?? 'ajouter';
$id = $_GET['id'] ?? null;
$joueur = null;

// Récupérer les données du joueur s’il s’agit d’une modification
if (($action === 'modifier' || $action === 'supprimer') && $id) {

    // Récupération des données via l'API
    $json = file_get_contents("https://footballmanagerapi.alwaysdata.net/joueur?id=$id");
    $response = json_decode($json, true);

    // Vérifie si la réponse contient des données valides
    if ($response) {
        $joueur = $response; // L'API renvoie directement un tableau avec les données du joueur
    } else {
        echo "Erreur : Données non trouvées pour ce joueur.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= ucfirst($action) ?> un joueur</title>
    <link rel="stylesheet" href="/FootAPI/gestion_api_front/public/assets/css/formulaire.css">
</head>
<body>
<main>
    <h1><?= ucfirst($action) ?> un joueur</h1>

    <?php if ($action !== 'supprimer'): ?>
        <form id="joueurForm">
            <input type="hidden" id="action" value="<?= $action ?>">
            <input type="hidden" id="id" value="<?= $id ?>">
            <label for="nom">Numéro de licence :</label>
            <input type="number" id="numero_licence" value="<?= $joueur['numero_licence'] ?? '' ?>">

            <label for="nom">Nom :</label>
            <input type="text" id="nom" required value="<?= $joueur['nom'] ?? '' ?>">

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" required value="<?= $joueur['prenom'] ?? '' ?>">

            <label for="date_naissance">Date de naissance :</label>
            <input type="date" id="date_naissance" required value="<?= $joueur['date_naissance'] ?? '' ?>">

            <label for="taille">Taille (m) :</label>
            <input type="number" step="0.01" id="taille" required value="<?= $joueur['taille'] ?? '' ?>">

            <label for="poids">Poids (kg) :</label>
            <input type="number" id="poids" required value="<?= $joueur['poids'] ?? '' ?>">

            <label for="statut">Statut :</label>
            <select id="statut">
                <option <?= isset($joueur) && $joueur['statut'] === 'Actif' ? 'selected' : '' ?>>Actif</option>
                <option <?= isset($joueur) && $joueur['statut'] === 'Blessé' ? 'selected' : '' ?>>Blessé</option>
                <option <?= isset($joueur) && $joueur['statut'] === 'Suspendu' ? 'selected' : '' ?>>Suspendu</option>
            </select>

            <div class="form-group">
                <label for="position_preferee">Position préférée :</label>
                <select id="position_preferee" name="position_preferee" required>
                    <option value="Gardien de but">Gardien de but</option>
                    <option value="Défenseur central">Défenseur central</option>
                    <option value="Défenseur latéral droit">Défenseur latéral droit</option>
                    <option value="Défenseur latéral gauche">Défenseur latéral gauche</option>
                    <option value="Milieu défensif">Milieu défensif</option>
                    <option value="Milieu central">Milieu central</option>
                    <option value="Milieu offensif">Milieu offensif</option>
                    <option value="Ailier droit">Ailier droit</option>
                    <option value="Ailier gauche">Ailier gauche</option>
                    <option value="Attaquant">Attaquant</option>
                </select>
            </div>

            <label for="commentaire">Commentaire :</label>
            <textarea id="commentaire"><?= $joueur['commentaire'] ?? '' ?></textarea>

            <button type="submit"><?= ucfirst($action) ?> joueur</button>
        </form>
    <?php else: ?>
        <input type="hidden" id="action" value="<?= $action ?>">
        <input type="hidden" id="id" value="<?= $id ?>">
    <?php endif; ?>

    <div id="message"></div>
</main>

<script src="/FootAPI/gestion_api_front/app/Controleurs/Joueur.js"></script>
</body>
</html>