<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $data = json_encode(['login' => $login, 'password' => $password]);

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => $data,
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents('https://footballmanagerauth.alwaysdata.net/auth', false, $context);

    if ($result === FALSE) {
        $error = "Erreur de connexion à l'API.";
    } else {
        $response = json_decode($result, true);
        if (isset($response['token'])) {
            $_SESSION['token'] = $response['token'];
            $_SESSION['utilisateur_id'] = $response['user_id']; // Set the user ID in the session

            // Redirect to the referrer URL or default to the home page
            $referrer = $_SESSION['referrer'] ?? '/FootAPI/gestion_api_front/app/vues/Accueil/accueil.php';
            header("Location: $referrer");
            exit;
        } else {
            $error = "Login ou mot de passe incorrect.";
        }
    }
} else {
    // Store the referrer URL
    $_SESSION['referrer'] = $_SERVER['HTTP_REFERER'] ?? '/FootAPI/gestion_api_front/app/vues/Accueil/accueil.php';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="/FootAPI/gestion_api_front/public/assets/css/login.css">
</head>
<body>
<div>
    <h2>Connexion</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="">
        <label for="login">Login :</label>
        <input type="text" id="login" name="login" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>
</div>
</body>
</html>