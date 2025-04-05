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

function ajouterJoueur(joueurData) {
    fetch(`${baseUrl}${resource}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(joueurData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status_code === 201) {
                alert("oueur ajouté avec succès !");
                getAllJoueurs(); // Refresh list
            } else {
                console.error("Erreur lors de l'ajout :", data.status_message);
            }
        })
        .catch(error => console.error('Erreur Fetch (POST):', error));
}

function modifierJoueur(numero_licence, joueurData) {
    fetch(`${baseUrl}${resource}?id=${numero_licence}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(joueurData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status_code === 200) {
                alert("✏️ Joueur modifié avec succès !");
                getAllJoueurs(); // Refresh list
            } else {
                console.error("Erreur modification :", data.status_message);
            }
        })
        .catch(error => console.error('Erreur Fetch (PUT):', error));
}

function deleteJoueur(numero_licence) {
    if (confirm("❗Êtes-vous sûr de vouloir supprimer ce joueur ?")) {
        fetch(`${baseUrl}${resource}?id=${numero_licence}`, {
            method: 'DELETE'
        })
            .then(response => response.json())
            .then(data => {
                if (data.status_code === 200) {
                    alert("🗑️ Joueur supprimé !");
                    getAllJoueurs(); // Refresh list
                } else {
                    console.error("Erreur suppression :", data.status_message);
                }
            })
            .catch(error => console.error('Erreur Fetch (DELETE):', error));
    }
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
            <a href="/FootAPI/gestion_api_front/form_joueur?action=modifier&id=${joueur.numero_licence}" class="btn-modifier">
                <i class="fas fa-edit"></i>
            </a>
            <a href="/FootAPI/gestion_api_front/form_joueur?action=supprimer&id=${joueur.numero_licence}" class="btn-supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce joueur ?');">
                <i class="fas fa-trash-alt"></i>
            </a>
        `;
    });
}

// Chargement des joueurs et ajout du bouton au démarrage
document.addEventListener("DOMContentLoaded", function() {
    getAllJoueurs(); // Charge automatiquement les joueurs
});
