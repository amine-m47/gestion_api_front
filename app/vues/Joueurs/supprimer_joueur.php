<?php
include __DIR__ . '/../Layouts/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un joueur</title>
</head>
<body>
<main>
    <h1>Suppression du joueur</h1>
</main>

<script>
        const urlParams = new URLSearchParams(window.location.search);
        const numeroLicence = urlParams.get('numero_licence');
        deleteJoueur(numeroLicence);
        window.location.href = 'liste_joueurs.php';
    });
</script>
<script src="/FootAPI/gestion_api_front/app/Controleurs/Joueur.js" defer></script>
</body>
</html>