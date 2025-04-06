const baseUrl = 'https://footballmanagerapi.alwaysdata.net/joueur';

// Méthode pour effectuer un appel API GET pour récupérer tous les joueurs
function getAllJoueurs() {
    fetch(baseUrl)
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

    const form = document.getElementById('joueurForm');
    const message = document.getElementById('message');
    const action = document.getElementById('action').value;
    const id = document.getElementById('id').value;

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            message.innerHTML = ''; // reset

            const joueurData = {
                numero_licence: document.getElementById('numero_licence').value,
                nom: document.getElementById('nom').value,
                prenom: document.getElementById('prenom').value,
                date_naissance: document.getElementById('date_naissance').value,
                taille: document.getElementById('taille').value,
                poids: document.getElementById('poids').value,
                statut: document.getElementById('statut').value,
                position_preferee: document.getElementById('position_preferee').value,
                commentaire: document.getElementById('commentaire').value
            };

            const erreurs = validerChamps(joueurData);

            if (erreurs.length > 0) {
                // Si des erreurs existent, on les affiche dans un message
                message.innerHTML = `<ul style="color: red;">${erreurs.map(e => `<li>${e}</li>`).join('')}</ul>`;
                return; // On arrête l'envoi du formulaire si des erreurs existent
            }

            let fetchOptions = {
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(joueurData)
            };

            try {
                let response;
                if (action === 'ajouter') {
                    fetchOptions.method = 'POST';
                    response = await fetch(baseUrl, fetchOptions);
                } else if (action === 'modifier') {
                    fetchOptions.method = 'PUT';
                    response = await fetch(`${baseUrl}?id=${id}`, fetchOptions);
                }

                const result = await response.json();
                if (response.ok) {
                    message.innerHTML = `<p style="color: green;">${action.charAt(0).toUpperCase() + action.slice(1)} en cours...</p>`;
                    setTimeout(() => window.location.href = 'joueurs', 1000);
                } else {
                    message.innerHTML = `<p style="color: red;">Erreur: ${result.status_message}</p>`;
                }
            } catch (error) {
                console.error("Erreur API:", error);
                message.innerHTML = `<p style="color: red;">Erreur de réseau.</p>`;
            }
        });
    }

    if (action === 'supprimer') {
        supprimerJoueur(id);
    }
});

function validerChamps(joueur) {
    const erreurs = [];

    if (!joueur.numero_licence.trim() || isNaN(joueur.numero_licence)) {
        erreurs.push("Le numéro de licence est obligatoire et doit être un chiffre.");
    }

    // Validation du nom, prénom et date de naissance
    if (!joueur.nom.trim() || !joueur.prenom.trim() || !joueur.date_naissance.trim()) {
        erreurs.push("Le nom, le prénom et la date de naissance sont obligatoires.");
    }

    // Validation de la taille
    const taille = parseFloat(joueur.taille);
    if (isNaN(taille) || taille < 1.30 || taille > 2.50) {
        erreurs.push("La taille doit être comprise entre 1.30 m et 2.50 m.");
    }

    // Validation du poids
    const poids = parseFloat(joueur.poids);
    if (isNaN(poids) || poids < 30 || poids > 300) {
        erreurs.push("Le poids doit être compris entre 30 kg et 300 kg.");
    }

    return erreurs;
}

// Suppression
async function supprimerJoueur(id) {
    try {
        const response = await fetch(`${baseUrl}?id=${id}`, { method: 'DELETE' });
        const result = await response.json();

        if (response.ok) {
            window.location.href = '/FootAPI/gestion_api_front/joueurs';
        } else {
            alert("Erreur : " + result.status_message);
        }
    } catch (error) {
        console.error("Erreur suppression:", error);
        alert("Une erreur est survenue.");
    }
}