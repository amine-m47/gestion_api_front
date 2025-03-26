<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/assets/css/rencontres.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Liste des Rencontres</title>
</head>
<body>
<main id="liste">
    <h1>Gestion des rencontres</h1>
    <div style="text-align: center;"><a href="ajouter_rencontre.php" class="btn-ajouter">Ajouter une rencontre</a></div>

    <div class="rencontres-container">
        <!-- Colonne des matchs passés -->
        <div class="column">
            <h2>Matchs Passés</h2>
            <div id="pastMatches"></div>
        </div>

        <!-- Colonne des matchs à venir -->
        <div class="column">
            <h2>Matchs à Venir</h2>
            <div id="upcomingMatches"></div>
        </div>
    </div>
</main>

<script>
    const baseUrl = 'http://localhost/FootAPI/gestion_api_back/Endpoint';
    const resource = '/RencontreEndpoint.php';

    // Fonction pour récupérer et afficher les rencontres
    async function fetchAndDisplayRencontres() {
        try {
            const response = await fetch(`${baseUrl}${resource}`);
            const rencontres = await response.json();
            displayRencontres(rencontres);
        } catch (error) {
            console.error('Erreur Fetch:', error);
        }
    }

    // Fonction pour afficher les rencontres
    function displayRencontres(rencontres) {
        const pastMatchesDiv = document.getElementById('pastMatches');
        const upcomingMatchesDiv = document.getElementById('upcomingMatches');
        pastMatchesDiv.innerHTML = '';
        upcomingMatchesDiv.innerHTML = '';

        rencontres.forEach(rencontre => {
            const matchDateTime = new Date(`${rencontre.date_rencontre} ${rencontre.heure_rencontre}`);
            const currentDateTime = new Date();
            const isMatchFutur = matchDateTime > currentDateTime;

            if (isMatchFutur) {
                upcomingMatchesDiv.appendChild(createMatchCard(rencontre, true));
            } else {
                pastMatchesDiv.appendChild(createMatchCard(rencontre, false));
            }
        });
    }

    // Fonction pour créer une carte de match
    function createMatchCard(rencontre, isFuture) {
        const matchCard = document.createElement('div');
        matchCard.className = 'match-card';

        const matchHeader = document.createElement('div');
        matchHeader.className = 'match-header';
        matchHeader.innerHTML = `
            <div class="match-date-time">
                <span class="match-date"><strong>${formatDate(rencontre.date_rencontre)}</strong> à </span>
                <span class="match-time"><strong>${rencontre.heure_rencontre}</strong> - </span>
                <span class="match-lieu">${rencontre.lieu}</span>
            </div>
            <div class="match-result">
                <strong><span style="color: ${getScoreColor(rencontre.score_equipe, rencontre.score_adverse)};">${rencontre.resultat}</span></strong>
            </div>
        `;

        const matchBody = document.createElement('div');
        matchBody.className = 'match-body';
        matchBody.innerHTML = `
            <div class="team">
                <div class="team-name team-end">${isFuture ? 'Équipe' : 'Nom Équipe'}</div>
                <span class="score" style="background-color: ${getScoreColor(rencontre.score_equipe, rencontre.score_adverse)};">${getScore(rencontre.score_equipe, rencontre.score_adverse, rencontre.lieu)}</span>
                <div class="team-name team-left">${rencontre.equipe_adverse}</div>
            </div>
        `;

        const matchFooter = document.createElement('div');
        matchFooter.className = 'match-footer';
        matchFooter.innerHTML = `
            <div class="actions">
                <a href="/feuille_rencontres.html?id_rencontre=${rencontre.id_rencontre}" class="btn-action">${isFuture ? 'Sélection' : 'Evaluations'}</a>
                <a href="/ajouter_resultat.html?id_rencontre=${rencontre.id_rencontre}" class="btn-action">${isFuture ? 'Modifier' : 'Score'}</a>
                <a href="#" class="btn-supprimer" onclick="confirmDelete(${rencontre.id_rencontre})">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>
        `;

        matchCard.appendChild(matchHeader);
        matchCard.appendChild(matchBody);
        matchCard.appendChild(matchFooter);

        return matchCard;
    }

    // Fonction pour formater la date en français
    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        return date.toLocaleDateString('fr-FR', options);
    }

    // Fonction pour déterminer la couleur du score
    function getScoreColor(scoreEquipe, scoreAdverse) {
        if (scoreEquipe > scoreAdverse) {
            return '#2dbc2d'; // Victoire
        } else if (scoreEquipe < scoreAdverse) {
            return 'red'; // Défaite
        } else if (scoreEquipe === scoreAdverse && scoreEquipe !== null && scoreAdverse !== null) {
            return 'white'; // Match nul
        }
        return '#1E1E1E';
    }

    // Fonction pour obtenir le score
    function getScore(scoreEquipe, scoreAdverse, lieu) {
        if (lieu === 'Domicile') {
            return scoreEquipe !== null && scoreAdverse !== null ? `${scoreEquipe} - ${scoreAdverse}` : '-';
        } else {
            return scoreEquipe !== null && scoreAdverse !== null ? `${scoreAdverse} - ${scoreEquipe}` : '-';
        }
    }

    // Fonction pour confirmer la suppression
    function confirmDelete(id_rencontre) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette rencontre ?')) {
            supprimer_rencontre(id_rencontre);
        }
    }

    // Appeler la fonction pour récupérer et afficher les rencontres au chargement de la page
    fetchAndDisplayRencontres();
</script>
</body>
</html>
