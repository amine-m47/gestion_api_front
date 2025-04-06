document.addEventListener('DOMContentLoaded', () => {
    const baseUrl = 'https://footballmanagerapi.alwaysdata.net'; // Base URL de l'API

    // Fonction pour récupérer les statistiques des rencontres et des joueurs
    async function fetchStatistics() {
        try {
            const response = await fetch(`${baseUrl}/statistique`);
            const data = await response.json();

            if (data.status_code === 200) {
                displayStatistics(data.data);
            } else {
                console.error('Erreur lors de la récupération des statistiques:', data.status_message);
            }
        } catch (error) {
            console.error('Erreur Fetch:', error);
        }
    }

    // Fonction pour afficher les statistiques des rencontres et des joueurs
    function displayStatistics(data) {
        const statsRencontres = data.statsRencontres;
        const joueurs = data.joueurs;

        // Afficher les statistiques des rencontres
        document.getElementById('victoires-pourcentage').textContent = `${statsRencontres.victoires_pourcentage}%`;
        document.getElementById('defaites-pourcentage').textContent = `${statsRencontres.defaites_pourcentage}%`;
        document.getElementById('nuls-pourcentage').textContent = `${statsRencontres.nuls_pourcentage}%`;

        document.getElementById('victoires').textContent = statsRencontres.victoires;
        document.getElementById('defaites').textContent = statsRencontres.defaites;
        document.getElementById('nuls').textContent = statsRencontres.nuls;
        document.getElementById('total-matchs').textContent = statsRencontres.total_matchs;

        // Afficher les statistiques des joueurs
        const joueursTableBody = document.getElementById('joueurs-table-body');
        joueursTableBody.innerHTML = ''; // Nettoyer le tableau avant de le remplir

        joueurs.forEach(async (joueur) => {
            const statsJoueurResponse = await fetch(`${baseUrl}/statistique?id=${joueur.numero_licence}`);
            const statsJoueurData = await statsJoueurResponse.json();

            if (statsJoueurData.status_code === 200) {
                const stats = statsJoueurData.data.statsJoueur;
                const row = joueursTableBody.insertRow();
                row.insertCell(0).textContent = joueur.prenom;
                row.insertCell(1).textContent = joueur.nom;
                row.insertCell(2).textContent = joueur.statut;
                row.insertCell(3).textContent = joueur.position_preferee;
                row.insertCell(4).textContent = stats.titularisations ?? 0;
                row.insertCell(5).textContent = stats.remplacements ?? 0;
                row.insertCell(6).textContent = `${stats.moyenne_notes ?? 0}/5`;
                row.insertCell(7).textContent = `${stats.pourcentage_victoires ?? 0}%`;
            }
        });
    }

    // Appeler la fonction pour récupérer les statistiques au chargement de la page
    fetchStatistics();
});