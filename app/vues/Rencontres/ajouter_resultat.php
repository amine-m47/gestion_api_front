<?php
include __DIR__ . '/../Layouts/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/FootAPI/gestion_api_front/public/assets/css/formulaire.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Ajouter Résultat - Rencontre</title>
</head>
<body>
<main id="resultats">
    <h1>Ajouter le Résultat de la Rencontre</h1>

    <form id="resultForm">
        <input type="hidden" id="id_rencontre" name="id_rencontre">

        <div>
            <label for="score_equipe">Score de notre équipe:</label>
            <input type="number" id="score_equipe" name="score_equipe" min="0" required>
        </div>

        <div>
            <label for="score_adverse">Score de l'Équipe adverse :</label>
            <input type="number" id="score_adverse" name="score_adverse" min="0" required>
        </div>

        <div class="buttons">
            <button type="submit">Ajouter Résultat</button>
        </div>
    </form>

    <div class="match-info">
        <h2>Informations de la rencontre</h2>
        <div><strong>Équipe adverse :</strong> <span id="equipe_adverse"></span></div>
        <div><strong>Date :</strong> <span id="date_rencontre"></span> à <span id="heure_rencontre"></span></div>
        <div><strong>Lieu :</strong> <span id="lieu"></span></div>
    </div>
</main>

<script>
    const baseUrl = 'https://footballmanagerapi.alwaysdata.net';
    const resource = `/rencontre`;

    // Fonction pour récupérer les données de la rencontre
    async function fetchRencontreData(id_rencontre) {
        try {
            const response = await fetch(`${baseUrl}${resource}?id_rencontre=${id_rencontre}`);
            const data = await response.json();

            if (data && data.id_rencontre) {
                displayRencontreData(data);
            } else {
                alert('Rencontre non trouvée');
            }
        } catch (error) {
            console.error('Erreur Fetch:', error);
        }
    }

    // Fonction pour afficher les informations de la rencontre dans le formulaire
    function displayRencontreData(rencontre) {
        document.getElementById('id_rencontre').value = rencontre.id_rencontre;
        document.getElementById('score_equipe').value = rencontre.score_equipe ?? '';
        document.getElementById('score_adverse').value = rencontre.score_adverse ?? '';
        document.getElementById('equipe_adverse').textContent = rencontre.equipe_adverse;
        document.getElementById('date_rencontre').textContent = formatDate(rencontre.date_rencontre);
        document.getElementById('heure_rencontre').textContent = rencontre.heure_rencontre;
        document.getElementById('lieu').textContent = rencontre.lieu;
    }

    // Fonction pour formater la date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    }

    // Fonction pour soumettre le résultat de la rencontre
    async function submitResult(event) {
        event.preventDefault();

        const id_rencontre = document.getElementById('id_rencontre').value;
        const score_equipe = document.getElementById('score_equipe').value;
        const score_adverse = document.getElementById('score_adverse').value;

        const resultData = {
            score_equipe: score_equipe,
            score_adverse: score_adverse
        };

        try {
            const response = await fetch(`${baseUrl}${resource}?id_rencontre=${id_rencontre}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(resultData)
            });

            const result = await response.json();
            if (response.ok) {
                alert('Résultat ajouté avec succès.');
                window.location.href = '/FootAPI/gestion_api_front/app/vues/Rencontres/liste_rencontres.php';
            } else {
                alert('Erreur lors de l\'ajout du résultat : ' + result.status_message);
            }
        } catch (error) {
            console.error('Erreur Fetch:', error);
            alert('Erreur de communication avec le serveur.');
        }
    }

    // Événement de soumission du formulaire
    document.getElementById('resultForm').addEventListener('submit', submitResult);

    // Récupérer l'ID de la rencontre depuis l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const id_rencontre = urlParams.get('id_rencontre');

    // Récupérer les données de la rencontre si l'ID est présent
    if (id_rencontre) {
        fetchRencontreData(id_rencontre);
    } else {
        alert('ID de la rencontre manquant.');
    }
</script>
</body>
</html>
