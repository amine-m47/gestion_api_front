<?php include __DIR__ . '/../Layouts/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/FootAPI/gestion_api_front/public/assets/css/formulaire.css">
    <script src="/FootAPI/gestion_api_front/app/Controleurs/Joueur.js" defer></script>
    <title>Modifier un joueur</title>
</head>
<body>
<main>
    <h1>Modifier un joueur</h1>

    <form id="modifierJoueurForm">
        <input type="hidden" id="numero_licence">

        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>

        <div class="form-group">
            <label for="date_naissance">Date de naissance :</label>
            <input type="date" id="date_naissance" name="date_naissance" required>
        </div>

        <div class="form-group">
            <label for="taille">Taille (en mètres) :</label>
            <input type="number" id="taille" name="taille" step="0.01" min="1.00" max="2.50" required>
        </div>

        <div class="form-group">
            <label for="poids">Poids (en kg) :</label>
            <input type="number" id="poids" name="poids" step="0.1" min="15" max="300" required>
        </div>

        <div class="form-group">
            <label for="statut">Statut :</label>
            <select id="statut" name="statut" required>
                <option value="Actif">Actif</option>
                <option value="Blessé">Blessé</option>
                <option value="Suspendu">Suspendu</option>
                <option value="Absent">Absent</option>
            </select>
        </div>

        <div class="form-group">
            <label for="position_preferee">Position préférée :</label>
            <input type="text" id="position_preferee" name="position_preferee" required>
        </div>

        <div class="form-group">
            <label for="commentaire">Commentaire :</label>
            <textarea id="commentaire" name="commentaire"></textarea>
        </div>

        <div class="form-group">
            <button type="submit">Modifier</button>
        </div>
    </form>
</main>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const numeroLicence = urlParams.get("numero_licence");

    document.addEventListener("DOMContentLoaded", () => {
        getJoueur(numeroLicence);
    });

    document.getElementById("modifierJoueurForm").addEventListener("submit", (event) => {
        event.preventDefault();
        updateJoueur(numeroLicence);
    });
</script>
</body>
</html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
