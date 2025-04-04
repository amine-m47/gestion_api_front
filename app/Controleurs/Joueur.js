const baseUrl = 'https://footballmanagerapi.alwaysdata.net';
const resource = '/joueur';

// Méthode pour effectuer un appel API GET pour récupérer tous les joueurs
function getAllJoueurs() {
    fetch(`${baseUrl}${resource}`)
        .then(response => response.json())
        .then(data => {
            console.log("🔍 Réponse API:", data); // Afficher la réponse complète pour debug

            if (Array.isArray(data)) { // Vérifie si data est bien un tableau
                displayData(data);
            } else {
                console.error('Erreur: format inattendu des données', data);
            }
        })
        .catch(error => console.error('Erreur Fetch:', error.message));
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
}

// Fonction pour ajouter dynamiquement le bouton "Ajouter un joueur"
function addButton() {
    const main = document.querySelector('main');
    if (main) {
        const button = document.createElement('a');
        button.className = 'btn-ajouter';
        button.href = 'ajouter_joueur';
        button.textContent = 'Ajouter un joueur';
        button.style.display = 'block';
        button.style.margin = '10px auto';
        button.style.textAlign = 'center';

        main.insertBefore(button, main.firstChild);
    } else {
        console.warn("L'élément <main> n'a pas été trouvé !");
    }
}

// Chargement des joueurs et ajout du bouton au démarrage
document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM chargé, exécution du script !");
    getAllJoueurs(); // Charge automatiquement les joueurs
    addButton(); // Ajoute le bouton "Ajouter un joueur"
});
