<?php
include __DIR__ . '/../Layouts/header.php';

$idRencontre = $_GET['id_rencontre'] ?? null;
if (!$idRencontre) {
    echo "Aucune rencontre sélectionnée.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Feuille de Match</title>
    <link rel="stylesheet" href="/FootAPI/gestion_api_front/public/assets/css/selection.css">
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const baseUrl = 'https://footballmanagerapi.alwaysdata.net/';
            const idRencontre = <?php echo json_encode($idRencontre); ?>;
            const tableCompo = document.querySelector(".table-compo tbody");
            const tableSelection = document.querySelector(".table-selection tbody");
            const form = document.querySelector("form");

            async function fetchData(endpoint) {
                const response = await fetch(`${baseUrl}${endpoint}`);
                return response.ok ? response.json() : null;
            }

            const rencontre = await fetchData(`/rencontre/${idRencontre}`);
            if (!rencontre) {
                document.body.innerHTML = "<p>Rencontre non trouvée.</p>";
                return;
            }

            const matchPasse = new Date(`${rencontre.date_rencontre} ${rencontre.heure_rencontre}`) < new Date();

// Appel de l'API pour récupérer les joueurs sélectionnés
            const joueursSelectionnesResponse = await fetchData(`/selection/${idRencontre}`);
            console.log(joueursSelectionnesResponse.data);
            const joueursSelectionnes = joueursSelectionnesResponse?.data?.joueurs_selectionnes ?? [];

// Récupération des notes existantes si le match est passé
            const notesExistantes = matchPasse ? await fetchData(`/selection?id_rencontre=${idRencontre}`) || {} : {};

// Appel de l'API pour récupérer la liste des joueurs actifs via SelectionEndpoint
            const joueursActifsResponse = await fetchData(`/SelectionEndpoint.php?id_rencontre=${idRencontre}`);
            const joueursActifs = joueursActifsResponse && joueursActifsResponse.joueurs_actifs ? joueursActifsResponse.joueurs_actifs : [];


            const postesFixes = ["GB", "DG", "DCG", "DCD", "DD", "MD", "MCG", "MCD", "AD", "AG", "BU", "R1", "R2", "R3", "R4", "R5"];
            const postesAssignes = Object.fromEntries(postesFixes.map(p => [p, null]));

            joueursSelectionnes.forEach(j => {
                if (j.poste) postesAssignes[j.poste] = j;
            });

            postesFixes.forEach(poste => {
                const joueur = postesAssignes[poste];
                const row = `<tr>
                    <td>${poste}</td>
                    <td>${joueur ? joueur.nom : '-'}</td>
                    <td>${joueur ? joueur.prenom : '-'}</td>
                    ${matchPasse ? `<td>
                        <select name="notes[${joueur?.numero_licence || ''}]">
                            <option value="">-- Choisir --</option>
                            ${[1,2,3,4,5].map(i => `<option value="${i}" ${notesExistantes[joueur?.numero_licence] == i ? 'selected' : ''}>${'★'.repeat(i)}</option>`).join('')}
                        </select>
                    </td>` : ''}
                </tr>`;
                tableCompo.insertAdjacentHTML("beforeend", row);
            });

            if (!matchPasse) {
                joueursActifs.forEach(joueur => {
                    const row = `<tr>
                        <td>${joueur.nom}</td>
                        <td>${joueur.prenom}</td>
                        <td>${joueur.position_preferee}</td>
                        <td>
                            <select class="postes" name="postes[${joueur.numero_licence}]">
                                <option value="">-- Choisir --</option>
                                ${postesFixes.map(poste => `<option value="${poste}" ${postesAssignes[poste]?.numero_licence == joueur.numero_licence ? 'selected' : ''}>${poste}</option>`).join('')}
                            </select>
                        </td>
                    </tr>`;
                    tableSelection.insertAdjacentHTML("beforeend", row);
                });
            }

            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const data = {
                    id_rencontre: idRencontre,
                    postes: Object.fromEntries([...formData.entries()].filter(([k]) => k.startsWith("postes["))),
                    notes: Object.fromEntries([...formData.entries()].filter(([k]) => k.startsWith("notes[")))
                };

                const response = await fetch(`${baseUrl}/SelectionEndpoint.php?id_rencontre=${idRencontre}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    window.location.href = "/football_manager/rencontres";
                } else {
                    alert("Erreur lors de l'enregistrement.");
                }
            });
        });
    </script>
</head>
<body>
<main id="fdm">
    <h1>Feuille de Match</h1>
    <form method="POST">
        <input type="hidden" name="id_rencontre" value="<?php echo htmlspecialchars($idRencontre); ?>">
        <div class="table-container">
            <table class="table-compo">
                <thead>
                <tr>
                    <th>Poste</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th id="note-column">Note</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            <table class="table-selection">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Position préférée</th>
                    <th>Poste</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <br>
        <input type="submit" value="Valider la sélection">
    </form>
</main>
</body>
</html>
