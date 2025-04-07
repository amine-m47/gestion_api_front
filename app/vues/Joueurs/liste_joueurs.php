<?php include __DIR__ . '/../Layouts/header.php';
header("Access-Control-Allow-Origin: *");
?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="public/assets/css/joueurs.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <title>Liste des joueurs</title>
    </head>
    <body>
    <div id="liste">
        <main>
            <h1>Gestion des joueurs</h1>

            <!-- Bouton pour ajouter un nouveau joueur -->
            <div style="text-align: center;"><a href="form_joueur?action=ajouter" class="btn-ajouter">➕ Ajouter un joueur</a></div>

            <!-- Tableau pour afficher la liste des joueurs -->
            <table border="1">
                <thead>
                <tr>
                    <th>Numéro de Licence</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de Naissance</th>
                    <th>Taille</th>
                    <th>Poids</th>
                    <th>Statut</th>
                    <th>Poste Préférée</th>
                    <th>Commentaire</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="responseTableBody">
                <!-- Les joueurs seront injectés ici en JavaScript -->
                </tbody>
            </table>
        </main>
    </div>

    <!-- Inclusion du fichier JavaScript pour gérer les joueurs -->
    <script src="app/Controleurs/Joueur.js" defer></script>

    </body>
    </html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>