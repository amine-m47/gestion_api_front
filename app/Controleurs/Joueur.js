const baseUrl = 'http://localhost/FootAPI/gestion_api_back/Endpoint';
const resource = '/JoueurEndpoint.php';

// Méthode pour effectuer un appel API GET pour récupérer tous les joueurs
function getAllJoueurs() {
    fetch(`${baseUrl}${resource}`).then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            displayData(data);
        })
        .catch(error => console.error('Erreur Fetch:', error));
}

// Méthode pour effectuer un appel API GET pour récupérer un joueur par numéro de licence
function getJoueur(numero_licence) {
    fetch(`${baseUrl}${resource}/${numero_licence}`)
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            displayData([data]);
        })
        .catch(error => console.error('Erreur Fetch:', error));
}

// Méthode pour créer un nouveau joueur
function addJoueur(joueurData) {
    const requestOptions = {
        method: 'POST', // Méthode HTTP
        headers: { 'Content-Type': 'application/json' }, // Type de contenu
        body: JSON.stringify(joueurData) // Corps de la requête
    };
    fetch(`${baseUrl}${resource}`, requestOptions).then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            console.log(data); // Afficher en console les données récupérées
        })
        .catch(error => console.error('Erreur Fetch:', error)); // Gérer les erreurs
}

// Méthode pour mettre à jour un joueur
function updateJoueur(numero_licence, joueurData) {
    const requestOptions = {
        method: 'PUT', // Méthode HTTP
        headers: { 'Content-Type': 'application/json' }, // Type de contenu
        body: JSON.stringify(joueurData) // Corps de la requête
    };
    fetch(`${baseUrl}${resource}/${numero_licence}`, requestOptions)
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            console.log(data); // Afficher en console les données récupérées
        })
        .catch(error => console.error('Erreur Fetch:', error)); // Gérer les erreurs
}

// Méthode pour supprimer un joueur
function deleteJoueur(numero_licence) {
    const requestOptions = {
        method: 'DELETE', // Méthode HTTP
        headers: { 'Content-Type': 'application/json' } // Type de contenu
    };
    fetch(`${baseUrl}${resource}/${numero_licence}`, requestOptions)
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            console.log(data); // Afficher en console les données récupérées
        })
        .catch(error => console.error('Erreur Fetch:', error)); // Gérer les erreurs
}

// Méthode pour afficher les données dans le tableau HTML
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
            <a class="btn-modifier" href="modifier_joueur.php?numero_licence=${joueur.numero_licence}">
                <i class="fas fa-edit"></i>
            </a>
            <a class="btn-supprimer" href="supprimer_joueur.php?numero_licence=${joueur.numero_licence}" onclick="return confirm('Voulez-vous vraiment supprimer ce joueur ?')">
                <i class="fas fa-trash-alt"></i>
            </a>
        `;
    });
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