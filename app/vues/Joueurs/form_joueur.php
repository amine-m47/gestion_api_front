<?php
include __DIR__ . '/../Layouts/header.php';

$action = $_GET['action'] ?? 'ajouter';
$id = $_GET['id'] ?? null;
$joueur = null;

// Récupérer les données du joueur s’il s’agit d’une modification
if (($action === 'modifier' || $action === 'supprimer') && $id) {
    // Affiche l'ID pour déboguer
    echo $id;

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
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                supprimerJoueur(); // Suppression immédiate
            });
        </script>
    <?php endif; ?>


    <div id="message"></div>
</main>

<script>
    const baseUrl = 'https://footballmanagerapi.alwaysdata.net/joueur';
    const action = '<?= $action ?>';
    const id = '<?= $id ?>';

    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById('joueurForm');
        const message = document.getElementById('message');

        if (form) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                message.innerHTML = ''; // reset

                const joueurData = {
                    numero_licence: document.getElementById('numero_licence').value,
                    nom: document.getElementById('nom').value,
                    prenom: document.getElementById('prenom').value,
                    date_naissance: document.getElementById('date_naissance').value,
                    taille: document.getElementById('taille').value,
                    poids: document.getElementById('poids').value,
                    statut: document.getElementById('statut').value,
                    position_preferee: document.getElementById('position_preferee').value,
                    commentaire: document.getElementById('commentaire').value
                };

                const erreurs = validerChamps(joueurData);

                if (erreurs.length > 0) {
                    // Si des erreurs existent, on les affiche dans un message
                    message.innerHTML = `<ul style="color: red;">${erreurs.map(e => `<li>${e}</li>`).join('')}</ul>`;
                    return; // On arrête l'envoi du formulaire si des erreurs existent
                }

                let fetchOptions = {
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(joueurData)
                };

                try {
                    let response;
                    if (action === 'ajouter') {
                        fetchOptions.method = 'POST';
                        response = await fetch(baseUrl, fetchOptions);
                    } else if (action === 'modifier') {
                        fetchOptions.method = 'PUT';
                        response = await fetch(`${baseUrl}?id=${id}`, fetchOptions);
                    }

                    const result = await response.json();
                    if (response.ok) {
                        message.innerHTML = `<p style="color: green;"><?= ucfirst($action) ?> en cours...</p>`;
                        setTimeout(() => window.location.href = 'joueurs', 1000);
                    } else {
                        message.innerHTML = `<p style="color: red;">Erreur: ${result.status_message}</p>`;
                    }
                } catch (error) {
                    console.error("Erreur API:", error);
                    message.innerHTML = `<p style="color: red;">Erreur de réseau.</p>`;
                }
            });
        }
    });

    function validerChamps(joueur) {
        const erreurs = [];

        if (!joueur.numero_licence.trim() || isNaN(joueur.numero_licence)) {
            erreurs.push("Le numéro de licence est obligatoire et doit être un chiffre.");
        }

        // Validation du nom, prénom et date de naissance
        if (!joueur.nom.trim() || !joueur.prenom.trim() || !joueur.date_naissance.trim()) {
            erreurs.push("Le nom, le prénom et la date de naissance sont obligatoires.");
        }

        // Validation de la taille
        const taille = parseFloat(joueur.taille);
        if (isNaN(taille) || taille < 1.30 || taille > 2.50) {
            erreurs.push("La taille doit être comprise entre 1.30 m et 2.50 m.");
        }

        // Validation du poids
        const poids = parseFloat(joueur.poids);
        if (isNaN(poids) || poids < 30 || poids > 300) {
            erreurs.push("Le poids doit être compris entre 30 kg et 300 kg.");
        }

        return erreurs;
    }


    // Suppression
    async function supprimerJoueur() {
        try {
            const response = await fetch(`${baseUrl}?id=${id}`, { method: 'DELETE' });
            const result = await response.json();

            if (response.ok) {
                window.location.href = '/FootAPI/gestion_api_front/joueurs';
            } else {
                alert("Erreur : " + result.status_message);
            }
        } catch (error) {
            console.error("Erreur suppression:", error);
            alert("Une erreur est survenue.");
        }
    }

</script>
</body>
</html>
