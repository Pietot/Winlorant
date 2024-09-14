<?php
include_once "php/winrate_functions.php";
include_once "php/headshot_functions.php";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Winlorant Tracker</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" />
</head>

<body>
    <header class="header">
        <div class="h1">
            <h1>Winlorant Tracker</h1>
        </div>
        <div class="header-links">

        </div>
    </header>
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