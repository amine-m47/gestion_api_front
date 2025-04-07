<?php
include __DIR__ . '/../Layouts/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/assets/css/rencontres.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Liste des Rencontres</title>
</head>
<body>
<main id="liste">
    <h1>Gestion des rencontres</h1>
    <div style="text-align: center;"><a href="ajouter_rencontre" class="btn-ajouter">Ajouter une rencontre</a></div>

    <div class="rencontres-container">
        <div class="column">
            <h2>Matchs Passés</h2>
            <div id="pastMatches"></div>
        </div>
        <div class="column">
            <h2>Matchs à Venir</h2>
            <div id="upcomingMatches"></div>
        </div>
    </div>
</main>

<script>
    const baseUrl = 'https://footballmanagerapi.alwaysdata.net';
    const resource = '/rencontre';
    const selection = '/selection';

    async function fetchAndDisplayRencontres() {
        try {
            const response = await fetch(`${baseUrl}${resource}`);
            const rencontres = await response.json();
            displayRencontres(rencontres);
        } catch (error) {
            console.error('Erreur Fetch:', error);
        }
    }

    async function fetchJoueursSelectionnes(id_rencontre) {
        try {
            const response = await fetch(`${baseUrl}${selection}/${id_rencontre}`);
            const data = await response.json();

            if (Array.isArray(data.data.joueurs_selectionnes)) {
                return data.data.joueurs_selectionnes.filter(joueur => !['R1', 'R2', 'R3', 'R4', 'R5'].includes(joueur.poste));
            } else {
                console.error("Données invalides : 'joueurs_selectionnes' n'est pas un tableau");
                return [];
            }
        } catch (error) {
            console.error('Erreur Fetch joueurs:', error);
            return [];
        }
    }

    function displayRencontres(rencontres) {
        const pastMatchesDiv = document.getElementById('pastMatches');
        const upcomingMatchesDiv = document.getElementById('upcomingMatches');
        pastMatchesDiv.innerHTML = '';
        upcomingMatchesDiv.innerHTML = '';

        rencontres.forEach(async (rencontre) => {
            const matchDateTime = new Date(`${rencontre.date_rencontre} ${rencontre.heure_rencontre}`);
            const currentDateTime = new Date();
            const isMatchFutur = matchDateTime > currentDateTime;

            const joueursSelectionnes = await fetchJoueursSelectionnes(rencontre.id_rencontre);
            const matchCard = createMatchCard(rencontre, isMatchFutur, joueursSelectionnes);

            if (isMatchFutur) {
                upcomingMatchesDiv.appendChild(matchCard);
            } else {
                pastMatchesDiv.appendChild(matchCard);
            }
        });
    }

    function createMatchCard(rencontre, isFuture, joueursSelectionnes) {
        const matchCard = document.createElement('div');
        matchCard.className = 'match-card';

        const matchHeader = document.createElement('div');
        matchHeader.className = 'match-header';
        matchHeader.innerHTML = `
            <div class="match-date-time">
                <strong>${formatDate(rencontre.date_rencontre)}</strong> à ${rencontre.heure_rencontre} - ${rencontre.lieu}
            </div>
            <div class="match-result">
                <strong><span style="color: ${getScoreColor(rencontre.score_equipe, rencontre.score_adverse)};">${rencontre.resultat}</span></strong>
            </div>`;

        const matchBody = document.createElement('div');
        matchBody.className = 'match-body';
        matchBody.innerHTML = `
            <div class="team">
                <div class="team-name team-end">France</div>
                <span class="score" style="background-color: ${getScoreColor(rencontre.score_equipe, rencontre.score_adverse)};">
                    ${getScore(rencontre.score_equipe, rencontre.score_adverse, rencontre.lieu)}
                </span>
                <div class="team-name team-left">${rencontre.equipe_adverse}</div>
            </div>`;
        const stadeContainer = document.createElement('div');
        stadeContainer.className = 'stade-container';
        const stadeImage = document.createElement('img');
        stadeImage.className = 'stade-image';
        stadeImage.src = 'public/assets/images/stade.jpg';
        stadeImage.alt = 'Stade';
        stadeContainer.appendChild(stadeImage);

        joueursSelectionnes.forEach(joueur => {
            const joueurDiv = document.createElement('div');
            joueurDiv.className = 'joueur ' + getJoueurPosition(joueur.poste);
            joueurDiv.innerText = `${joueur.nom} ${joueur.prenom}`;
            stadeContainer.appendChild(joueurDiv);
        });

        matchBody.appendChild(stadeContainer);

        const matchFooter = document.createElement('div');
        matchFooter.className = 'match-footer';
        matchFooter.innerHTML = `
            <div class="actions">
                <a href="selection?id_rencontre=${rencontre.id_rencontre}" class="btn-action">
                    ${isFuture ? 'Sélection' : 'Evaluations'}
                </a>
                <a href="${isFuture
            ? `modifier_rencontre?id_rencontre=${rencontre.id_rencontre}`
            : `score?id_rencontre=${rencontre.id_rencontre}`}" class="btn-action">
                    ${isFuture ? 'Modifier' : 'Score'}
                </a>
                <a href="#" class="btn-supprimer" onclick="confirmDelete(${rencontre.id_rencontre})">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>`;

        matchCard.appendChild(matchHeader);
        matchCard.appendChild(matchBody);
        matchCard.appendChild(matchFooter);
        return matchCard;
    }
    function confirmDelete(id_rencontre) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?')) {
            supprimer_rencontre(id_rencontre);
        }
    }

    async function supprimer_rencontre(id_rencontre) {
        try {
            const response = await fetch(`${baseUrl}${resource}?id_rencontre=${id_rencontre}`, {
                method: 'DELETE',
                headers: {'Content-Type': 'application/json'}
            });

            const result = await response.json();
            console.log("Réponse de suppression :", result);

            if (response.ok) {
                fetchAndDisplayRencontres();
            } else {
                alert('Erreur lors de la suppression : ' + result.status_message);
            }
        } catch (error) {
            console.error('Erreur Fetch:', error);
        }
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    }

    function getScoreColor(scoreEquipe, scoreAdverse) {
        if (scoreEquipe > scoreAdverse) return '#2dbc2d';
        if (scoreEquipe < scoreAdverse) return 'red';
        if (scoreEquipe === scoreAdverse && scoreEquipe !== null) return 'white';
        return '#1E1E1E';
    }

    function getScore(scoreEquipe, scoreAdverse, lieu) {
        if (lieu === 'Domicile') {
            return scoreEquipe !== null && scoreAdverse !== null ? `${scoreEquipe} - ${scoreAdverse}` : '-';
        } else {
            return scoreEquipe !== null && scoreAdverse !== null ? `${scoreAdverse} - ${scoreEquipe}` : '-';
        }
    }

    function getJoueurPosition(poste) {
        const positions = {
            "GB": "gb",
            "DG": "dg",
            "DCG": "dcg",
            "DCD": "dcd",
            "DD": "dd",
            "MD": "md",
            "MCG": "mcg",
            "MCD": "mcd",
            "AD": "ad",
            "AG": "ag",
            "BU": "bu"
        };
        return positions[poste] || "bu";  // Position par défaut si non défini
    }

    fetchAndDisplayRencontres();
</script>

</body>
</html>
