const baseUrl = 'https://footballmanagerapi.alwaysdata.net';
const resource = '/rencontre';

// Méthode pour effectuer un appel API GET pour récupérer tous les rencontres
function liste_rencontre() {
    fetch(`${baseUrl}${resource}`).then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            displayData(data);
        })
        .catch(error => console.error('Erreur Fetch:', error));
}

// Méthode pour effectuer un appel API GET pour récupérer un rencontre par numéro de licence
function getRencontreById(id_rencontre) {
    fetch(`${baseUrl}${resource}/${id_rencontre}`)
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            displayData([data]);
        })
        .catch(error => console.error('Erreur Fetch:', error));
}

// Méthode pour créer un nouveau rencontre
function ajouter_rencontre(rencontreData) {
    const requestOptions = {
        method: 'POST', // Méthode HTTP
        headers: { 'Content-Type': 'application/json' }, // Type de contenu
        body: JSON.stringify(rencontreData) // Corps de la requête
    };
    fetch(`${baseUrl}${resource}`, requestOptions).then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            console.log(data); // Afficher en console les données récupérées
        })
        .catch(error => console.error('Erreur Fetch:', error)); // Gérer les erreurs
}

// Méthode pour mettre à jour un rencontre
function modifier_rencontre(id_rencontre, rencontreData) {
    const requestOptions = {
        method: 'PUT', // Méthode HTTP
        headers: { 'Content-Type': 'application/json' }, // Type de contenu
        body: JSON.stringify(rencontreData) // Corps de la requête
    };
    fetch(`${baseUrl}${resource}/${id_rencontre}`, requestOptions)
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            console.log(data); // Afficher en console les données récupérées
        })
        .catch(error => console.error('Erreur Fetch:', error)); // Gérer les erreurs
}

// Méthode pour supprimer un rencontre
function supprimer_rencontre(numero_licence) {
    const requestOptions = {
        method: 'DELETE', // Méthode HTTP
        headers: { 'Content-Type': 'application/json' } // Type de contenu
    };
    fetch(`${baseUrl}${resource}/${id_rencontre}`, requestOptions)
        .then(response => response.json()) // Convertir la réponse en JSON
        .then(data => {
            console.log(data); // Afficher en console les données récupérées
        })
        .catch(error => console.error('Erreur Fetch:', error)); // Gérer les erreurs
}

// Méthode pour afficher les données dans le tableau HTML
function displayData(rencontres) {
    const tableBody = document.getElementById('responseTableBody');
    tableBody.innerHTML = ''; // Nettoie le tableau avant de le remplir
    rencontres.forEach(rencontre => {
        const row = tableBody.insertRow();
        row.insertCell(0).textContent = rencontre.id_rencontre;
        row.insertCell(1).textContent = rencontre.equipe_adverse;
        row.insertCell(2).textContent = rencontre.heure_rencontre;
        row.insertCell(3).textContent = rencontre.lieu;
        row.insertCell(4).textContent = rencontre.score_equipe;
        row.insertCell(5).textContent = rencontre.score_adverse;
        row.insertCell(6).textContent = rencontre.resultat;
    });
}

// Attacher les événements aux boutons
document.getElementById('getAllRencontres').addEventListener('click', getAllRencontres);
document.getElementById('getRencontre').addEventListener('click', () => {
    const id_rencontre = document.getElementById('rencontreID').value;
    getRencontreById(id_rencontre);
});
document.getElementById('addRencontre').addEventListener('click', () => {
    const rencontreData = {
        equipe_adverse: document.getElementById('addEquipeAdverse').value,
        heure_rencontre: document.getElementById('addHeureRencontre').value,
        lieu: document.getElementById('addLieu').value,
        score_equipe: document.getElementById('addScoreEquipe').value,
        score_adverse: document.getElementById('addScoreAdverse').value,
        resultat: document.getElementById('AddResultat').value
    };
    ajouter_rencontre(rencontreData);
});
document.getElementById('updateRencontre').addEventListener('click', () => {
    const id_rencontre = document.getElementById('updateIdRencontre').value;
    const rencontreData = {
        equipe_adverse: document.getElementById('updateEquipeAdverse').value,
        heure_rencontre: document.getElementById('updateHeureRencontre').value,
        lieu: document.getElementById('updateLieu').value,
        score_equipe: document.getElementById('updateScoreEquipe').value,
        score_adverse: document.getElementById('updateScoreAdverse').value,
        resultat: document.getElementById('updateResultat').value
    };
    modifier_rencontre(id_rencontre, rencontreData);
});
document.getElementById('deleterencontre').addEventListener('click', () => {
    const id_rencontre = document.getElementById('deleteIdRencontre').value;
    supprimer_rencontre(id_rencontre);
});