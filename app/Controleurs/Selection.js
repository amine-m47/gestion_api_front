const baseUrl = 'http://localhost/FootAPI/gestion_api_back/Endpoint';
const resource = '/SelectionEndpoint.php';

// Récupérer toutes les sélections
function liste_selection() {
    fetch(`${baseUrl}${resource}`)
        .then(response => response.json())
        .then(data => displayData(data))
        .catch(error => console.error('Erreur Fetch:', error));
}

// Récupérer une sélection par ID
function getSelectionById(id_selection) {
    fetch(`${baseUrl}${resource}/${id_selection}`)
        .then(response => response.json())
        .then(data => displayData([data]))
        .catch(error => console.error('Erreur Fetch:', error));
}

// Ajouter une sélection
function ajouter_selection(selectionData) {
    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(selectionData)
    };
    fetch(`${baseUrl}${resource}`, requestOptions)
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error('Erreur Fetch:', error));
}

// Modifier une sélection
function modifier_selection(id_selection, selectionData) {
    const requestOptions = {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(selectionData)
    };
    fetch(`${baseUrl}${resource}/${id_selection}`, requestOptions)
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error('Erreur Fetch:', error));
}

// Supprimer une sélection
function supprimer_selection(id_selection) {
    const requestOptions = {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' }
    };
    fetch(`${baseUrl}${resource}/${id_selection}`, requestOptions)
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error('Erreur Fetch:', error));
}

// Affichage des données dans le tableau HTML
function displayData(selections) {
    const tableBody = document.getElementById('responseTableBody');
    tableBody.innerHTML = '';
    selections.forEach(selection => {
        const row = tableBody.insertRow();
        row.insertCell(0).textContent = selection.id_selection;
        row.insertCell(1).textContent = selection.joueur_id;
        row.insertCell(2).textContent = selection.match_id;
        row.insertCell(3).textContent = selection.poste;
        row.insertCell(4).textContent = selection.statut;
    });
}

// Attachement des événements aux boutons
document.getElementById('getAllSelections').addEventListener('click', liste_selection);
document.getElementById('getSelection').addEventListener('click', () => {
    const id_selection = document.getElementById('selectionID').value;
    getSelectionById(id_selection);
});
document.getElementById('addSelection').addEventListener('click', () => {
    const selectionData = {
        joueur_id: document.getElementById('addJoueurId').value,
        match_id: document.getElementById('addMatchId').value,
        poste: document.getElementById('addPoste').value,
        statut: document.getElementById('addStatut').value
    };
    ajouter_selection(selectionData);
});
document.getElementById('updateSelection').addEventListener('click', () => {
    const id_selection = document.getElementById('updateIdSelection').value;
    const selectionData = {
        joueur_id: document.getElementById('updateJoueurId').value,
        match_id: document.getElementById('updateMatchId').value,
        poste: document.getElementById('updatePoste').value,
        statut: document.getElementById('updateStatut').value
    };
    modifier_selection(id_selection, selectionData);
});
document.getElementById('deleteSelection').addEventListener('click', () => {
    const id_selection = document.getElementById('deleteIdSelection').value;
    supprimer_selection(id_selection);
});
