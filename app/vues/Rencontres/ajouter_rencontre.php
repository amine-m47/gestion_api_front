<?php
include __DIR__ . '/../Layouts/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/FootAPI/gestion_api_front/public/assets/css/formulaire.css">
    <title>Ajouter une rencontre</title>
</head>
<body>
<main>
    <h1>Ajouter une rencontre</h1>
    <form id="rencontreForm">
        <label for="equipe_adverse">Équipe Adverse :</label>
        <input type="text" id="equipe_adverse" name="equipe_adverse" pattern="[A-Za-zÀ-ÿ]+" required>

        <label for="date_rencontre">Date de la rencontre :</label>
        <input type="date" id="date_rencontre" name="date_rencontre" required min="<?= date("Y-m-d") ?>">

        <label for="heure_rencontre">Heure de la rencontre :</label>
        <input type="time" id="heure_rencontre" name="heure_rencontre" required>

        <label for="lieu">Lieu :</label>
        <select id="lieu" name="lieu" required>
            <option value="Domicile">Domicile</option>
            <option value="Exterieur">Exterieur</option>
        </select>

        <button type="submit">Ajouter</button>
    </form>
    <div id="message"></div>
</main>

<script>
    const baseUrl = 'http://localhost/FootAPI/gestion_api_back/Endpoint';
    const resource = '/RencontreEndpoint.php';

    document.getElementById('rencontreForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        const messageDiv = document.getElementById('message');
        messageDiv.innerHTML = '';

        const equipe_adverse = document.getElementById('equipe_adverse').value;
        const date_rencontre = document.getElementById('date_rencontre').value;
        const heure_rencontre = document.getElementById('heure_rencontre').value;
        const lieu = document.getElementById('lieu').value;

        const currentDateTime = new Date();
        const dateTimeRendezvous = new Date(`${date_rencontre}T${heure_rencontre}`);

        if (dateTimeRendezvous <= currentDateTime) {
            messageDiv.innerHTML = '<p style="color: red;">Erreur : La date et l\'heure de la rencontre doivent être supérieures à la date et l\'heure actuelles.</p>';
        } else {
            const rencontreData = {
                equipe_adverse,
                date_rencontre,
                heure_rencontre,
                lieu
            };

            try {
                const response = await fetch(`${baseUrl}${resource}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(rencontreData)
                });

                if (response.ok) {
                    messageDiv.innerHTML = '<p style="color: green;">Rencontre ajoutée avec succès!</p>';
                    window.location.href = '/FootAPI/gestion_api_front/app/vues/Rencontres/liste_rencontres.php';
                } else {
                    messageDiv.innerHTML = '<p style="color: red;">Erreur lors de l\'ajout de la rencontre.</p>';
                }

            } catch (error) {
                console.error('Erreur Fetch:', error);
                messageDiv.innerHTML = '<p style="color: red;">Erreur réseau. Veuillez réessayer.</p>';
            }
        }
    });
</script>
</body>
</html>
