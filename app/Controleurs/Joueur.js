const baseUrl = 'http://localhost/FootAPI/gestion_api_back/Endpoint';
const resource = '/JoueurEndpoint.php';

// Méthode pour effectuer un appel API GET pour récupérer tous les joueurs
function getAllJoueurs() {
    fetch(`${baseUrl}${resource}`)
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            if (data.status_code === 200) {
                displayData(data.data);
            } else {
                console.error('Erreur lors de la récupération des joueurs:', data.status_message);
            }
        })
        .catch(error => console.error('Erreur Fetch:', error));
}

// Méthode pour afficher les données dans le tableau HTML
function displayData(joueurs) {
    const tableBody = document.getElementById('responseTableBody');
    tableBody.innerHTML = ''; // Nettoie le tableau avant de le remplir

    joueurs.forEach(joueur => {
        const row = tableBody.insertRow();
        row.insertCell(0).textContent = joueur.numero_licence;
        row.insertCell(1).textContent = joueur.nom;
        row.insertCell(2).textContent = joueur.prenom;
        row.insertCell(3).textContent = joueur.date_naissance;
        row.insertCell(4).textContent = joueur.taille + ' m';
        row.insertCell(5).textContent = joueur.poids + ' kg';
        row.insertCell(6).textContent = joueur.statut;
        row.insertCell(7).textContent = joueur.position_preferee;
        row.insertCell(8).textContent = joueur.commentaire;

        // Ajouter les actions Modifier et Supprimer
        const actionsCell = row.insertCell(9);
        actionsCell.innerHTML = `
            <button class="btn-modifier" onclick="prepareUpdate('${joueur.numero_licence}')">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn-supprimer" onclick="deleteJoueur('${joueur.numero_licence}')">
                <i class="fas fa-trash-alt"></i>
            </button>
        `;
    });
}

// Fonction pour préparer la mise à jour d'un joueur
function prepareUpdate(numero_licence) {
    document.getElementById('updateNumeroLicence').value = numero_licence;
    // Remplir les champs de mise à jour avec les données du joueur
    // Vous pouvez appeler getJoueur(numero_licence) pour obtenir les données du joueur et les insérer dans les champs
}

// Attacher les événements aux boutons
document.getElementById('getAllJoueurs').addEventListener('click', getAllJoueurs);
document.getElementById('getJoueur').addEventListener('click', () => {
    const numero_licence = document.getElementById('joueurID').value;
    getJoueur(numero_licence);
});
document.getElementById('addJoueur').addEventListener('click', () => {
    const joueurData = {
        numero_licence: document.getElementById('newNumeroLicence').value,
        nom: document.getElementById('newNom').value,
        prenom: document.getElementById('newPrenom').value,
        date_naissance: document.getElementById('newDateNaissance').value,
        taille: document.getElementById('newTaille').value,
        poids: document.getElementById('newPoids').value,
        statut: document.getElementById('newStatut').value,
        position_preferee: document.getElementById('newPositionPreferee').value,
        commentaire: document.getElementById('newCommentaire').value
    };
    addJoueur(joueurData);
});
document.getElementById('updateJoueur').addEventListener('click', () => {
    const numero_licence = document.getElementById('updateNumeroLicence').value;
    const joueurData = {
        nom: document.getElementById('updateNom').value,
        prenom: document.getElementById('updatePrenom').value,
        date_naissance: document.getElementById('updateDateNaissance').value,
        taille: document.getElementById('updateTaille').value,
        poids: document.getElementById('updatePoids').value,
        statut: document.getElementById('updateStatut').value,
        position_preferee: document.getElementById('updatePositionPreferee').value,
        commentaire: document.getElementById('updateCommentaire').value
    };
    updateJoueur(numero_licence, joueurData);
});
document.getElementById('deleteJoueur').addEventListener('click', () => {
    const numero_licence = document.getElementById('deleteNumeroLicence').value;
    deleteJoueur(numero_licence);
});

// Chargement des joueurs au démarrage
document.addEventListener("DOMContentLoaded", function() {
    getAllJoueurs(); // Charge automatiquement les joueurs à l'ouverture de la page
});