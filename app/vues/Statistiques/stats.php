<?php include __DIR__ . '/../Layouts/header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/FootAPI/gestion_api_front/public/assets/css/stats.css">
    <link rel="stylesheet" href="/FootAPI/gestion_api_front/public/assets/css/charts.min.css">
    <title>Statistiques des Joueurs</title>
</head>
<body>
<div id="stats">
    <main>
        <h2>Statistiques des Rencontres</h2>
        <div id="my-chart">
            <table class="charts-css pie hide-data">
                <caption>Statistiques des matchs</caption>
                <thead>
                <tr>
                    <th scope="col">Matchs</th>
                    <th scope="col">Pourcentage</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Matchs gagnés</th>
                    <td id="stats-gagnes" style="--color: #35b135;">
                        <span class="data" id="victoires-pourcentage"></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Matchs perdus</th>
                    <td id="stats-perdus" style="--color: #da3939;">
                        <span class="data" id="defaites-pourcentage"></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Matchs nuls</th>
                    <td id="stats-nuls" style="--color: #d8c9c9;">
                        <span class="data" id="nuls-pourcentage"></span>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="stats_matchs">
                <p><span style="color: #35b135;">&#9608;&#9608;&#9608;&#9608;</span> Matchs gagnés : <span id="victoires"></span></p>
                <p><span style="color: #da3939;">&#9608;&#9608;&#9608;&#9608;</span> Matchs perdus : <span id="defaites"></span></p>
                <p><span style="color: #d8c9c9;">&#9608;&#9608;&#9608;&#9608;</span> Matchs nuls : <span id="nuls"></span></p></br>
                <p class="total">Nombre total de matchs : <span id="total-matchs"></span></p>
            </div>
        </div>

        <h2>Statistiques des Joueurs</h2>
        <table class="stats_joueurs">
            <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Statut</th>
                <th>Poste Préféré</th>
                <th>Titularisations</th>
                <th>Remplaçant</th>
                <th>Moyenne</th>
                <th>Victoires</th>
            </tr>
            </thead>
            <tbody id="joueurs-table-body">
            <!-- Les lignes du tableau seront ajoutées ici via JavaScript -->
            </tbody>
        </table>
    </main>
</div>
<?php include __DIR__ . '/../Layouts/footer.php'; ?>
<script src="../../Controleurs/Statistiques.js"></script>
</body>
</html>
