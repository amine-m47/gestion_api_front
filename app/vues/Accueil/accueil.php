<?php
include __DIR__ . '/../Layouts/header.php';

$token = $_SESSION['token'] ?? null;
echo $token;
$message = "";
$fullName = "";
$infosUtilisateur = [];

if ($token) {
    $options = [
        'http' => [
            'header' => "Authorization: Bearer $token\r\nContent-Type: application/json\r\n",
            'method' => 'GET',
        ],
    ];
    $context = stream_context_create($options);
    $result = file_get_contents('https://footballmanagerauth.alwaysdata.net/auth', false, $context);

    if ($result !== FALSE) {
        $response = json_decode($result, true);
        if (isset($response['data'])) {
            $infosUtilisateur = $response['data'];
            $fullName = htmlspecialchars($infosUtilisateur['login']) . " " . htmlspecialchars($infosUtilisateur['nom']);
        }
    }
}

// Traiter la mise à jour si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_encode([
        'login' => $_POST['login']
    ]);

    $options = [
        'http' => [
            'header' => "Authorization: Bearer $token\r\nContent-Type: application/json\r\n",
            'method' => 'POST',
            'content' => $data,
        ],
    ];
    $context = stream_context_create($options);
    $result = file_get_contents('https://footballmanagerauth.alwaysdata.net/auth/update', false, $context);

    if ($result !== FALSE) {
        $response = json_decode($result, true);
        if ($response['status_code'] == 200) {
            $message = "Informations mises à jour avec succès.";
            header("refresh:1;url=../Accueil/accueil");
        } else {
            $message = "Erreur lors de la mise à jour des informations.";
        }
    }
}
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
                Bienvenue <?= isset($_SESSION['token']) ? htmlspecialchars($fullName) : "sur Football Management" ?>
            </h1>
    </main>
    </body>
    </html>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>