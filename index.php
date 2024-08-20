<?php
include_once "php/winrate_functions.php";
include_once "php/headshot_functions.php";

echo "<h1>" . get_number_game() . " parties ont été analysé. Ce nombre sera actualisé automatiquement tous les dimanches.</h1><br>";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Graphique</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" />
</head>

<body>
    <div class="chart-container">
        <canvas id="dailyChart"></canvas>
    </div>
    <br>
    <br>
    <br>
    <br>
    <div class="chart-container">
        <canvas id="mapChart"></canvas>
    </div>
    <br>
    <br>
    <br>
    <br>
    <script src="js/chart.min.js"></script>
    <script src="js/chart_configuration.js"></script>
</body>

</html>