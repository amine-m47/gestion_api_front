<?php
include __DIR__ . '/../Layouts/header.php';

$token = $_SESSION['token'] ?? null;
echo $token;
?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Page d'accueil</title>
        <link rel="stylesheet" href="/FootAPI/gestion_api_front/public/assets/css/accueil.css">
    </head>
    <body>
    <main>
        <div class="welcome-container">
            <h1>
                Bienvenue <?= isset($_SESSION['token']) ? "Zidane Football Management" : "sur Football Management" ?>
            </h1>
    </main>
    </body>
    </html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>