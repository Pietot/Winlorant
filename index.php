<?php
include_once "php/chart.php";


echo "<h1>" . get_number_game() . " parties ont été analysé. Ce nombre sera actualisé automatiquement tous les dimanches.</h1><br>";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Graphique en courbe</title>
    <link rel="stylesheet" type="text/css" href="css/chart.css" />
    <link rel="stylesheet" type="text/css" href="css/index.css" />
</head>

<body>
    <div class="chart-container">
        <canvas id="myChart"></canvas>
    </div>
    <script src="js/chart.min.js"></script>
    <script src="js/chart_configuration.js"></script>
</body>

</html>