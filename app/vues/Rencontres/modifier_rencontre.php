<?php
include __DIR__ . '/../Layouts/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/FootAPI/gestion_api_front/public/assets/css/formulaire.css">
    <title>Modifier une rencontre</title>
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const urlParams = new URLSearchParams(window.location.search);
            const id_rencontre = urlParams.get("id_rencontre");

            if (!id_rencontre) {
                alert("ID de la rencontre non fourni.");
                return;
            }

            const baseUrl = 'https://footballmanagerapi.alwaysdata.net';
            const resource = `/rencontre?id_rencontre=${id_rencontre}`;
            const apiUrl = `${baseUrl}${resource}`;

            try {
                const response = await fetch(apiUrl, { method: "GET" });

                if (!response.ok) throw new Error("Rencontre non trouvée");

                const rencontre = await response.json();

                document.getElementById("equipe_adverse").value = rencontre.equipe_adverse;
                document.getElementById("date_rencontre").value = rencontre.date_rencontre;
                document.getElementById("heure_rencontre").value = rencontre.heure_rencontre;
                document.getElementById("lieu").value = rencontre.lieu;
            } catch (error) {
                alert(error.message);
            }

            // Gestion du formulaire de modification
            document.getElementById("modifier-rencontre-form").addEventListener("submit", async (event) => {
                event.preventDefault();

                const equipe_adverse = document.getElementById("equipe_adverse").value;
                const date_rencontre = document.getElementById("date_rencontre").value;
                const heure_rencontre = document.getElementById("heure_rencontre").value;
                const lieu = document.getElementById("lieu").value;

                const currentDateTime = new Date();
                const dateTimeRendezvous = new Date(`${date_rencontre}T${heure_rencontre}`);

                if (dateTimeRendezvous <= currentDateTime) {
                    alert("Erreur : La date et l'heure doivent être supérieures à la date et l'heure actuelles.");
                    return;
                }

                const rencontreData = { equipe_adverse, date_rencontre, heure_rencontre, lieu };

                try {
                    const response = await fetch(apiUrl, {
                        method: "PUT",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(rencontreData)
                    });

                    if (!response.ok) throw new Error("Erreur lors de la modification");
                    window.location.href = '/FootAPI/gestion_api_front/rencontres';
                } catch (error) {
                    alert(error.message);
                }
            });
        });
    </script>
</head>
<body>
<div id="modifier-style">
    <main>
        <h1>Modifier une rencontre</h1>
        <form id="modifier-rencontre-form">
            <div class="form-group">
                <label for="equipe_adverse">Équipe Adverse :</label>
                <input type="text" id="equipe_adverse" name="equipe_adverse" pattern="[A-Za-zÀ-ÿ]+" required>
            </div>
            <div class="form-group">
                <label for="date_rencontre">Date :</label>
                <input type="date" id="date_rencontre" name="date_rencontre" required>
            </div>
            <div class="form-group">
                <label for="heure_rencontre">Heure :</label>
                <input type="time" id="heure_rencontre" name="heure_rencontre" required>
            </div>
            <div class="form-group">
                <label for="lieu">Lieu :</label>
                <select id="lieu" name="lieu" required>
                    <option value="Domicile">Domicile</option>
                    <option value="Exterieur">Exterieur</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Modifier</button>
            </div>
        </form>
    </main>
</div>
</body>
</html>
