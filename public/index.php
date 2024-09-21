<?php
session_start();
include_once '../src/php/is_registered.php';
include '../src/php/db.php';

$userdata = is_registered($db);

if ($userdata) {
    $_SESSION['username'] = $userdata[0];
    $_SESSION['tag'] = $userdata[1];
    $_SESSION['region'] = $userdata[2];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Winlorant</title>
    <link rel="icon" type="image/x-icon" href="assets/icon.svg">
    <link rel="stylesheet" type="text/css" href="css/index.css" />
</head>

<body>
    <header class="header">
        <div class="header-links">
            <a class="home" href="index.php">Winlorant</a>
        </div>
        <div class="header-links">
            <a class="about" href="about.php">About</a>
        </div>
        <div class="header-links">
            <a class="contact" href="contact.php">Contact</a>
        </div>
        <?php
        if ($userdata) {
            echo '<div class="header-links">' . "\n";
            echo '<p class="user" href="#">' . $_SESSION["username"] . '#' . $_SESSION["tag"] . "</p>\n";
            echo '</div>';
            echo '<div class="header-links">';
            echo '<a class="signout" href="../src/php/signout.php">Sign out</a>';
            echo '</div>';
        } else {
            echo '<div class="header-links">';
            echo '<a class="register" href="register.php">Register</a>';
            echo '</div>';
        }
        ?>
    </header>
    <?php
    if ($userdata) {
        include_once '../src/php/winrate_functions.php';
        include_once '../src/php/get_acts.php';
        echo '<div>';
        echo '<p class="text-info">' . get_number_game($_SESSION['username'], $_SESSION['tag']) . ' games tracked. This number will be updated each day at ~00:00 UTC</p>';
        echo '</div>';
        echo '<div class="select-container">';
        echo '<select id="select" onchange="updateChart(this.value)">';
        echo '<option value="null" selected>All time</option>';
        $acts = get_acts();
        foreach ($acts as $act) {
            echo '<option value="' . $act . '">' . $act . '</option>';
        }
        echo '</select>';
        echo '</div>';
        echo '<div class="chart-container">';
        echo '<canvas id="dailyChart"></canvas>';
        echo '</div>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<div class="chart-container">';
        echo '<canvas id="mapChart"></canvas>';
        echo '</div>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<script src="js/chart.min.js"></script>';
        echo '<script src="js/chart_configuration.js"></script>';
        echo '<script src="js/scroll.js"></script>';
    } else {
        echo '<div class="text-container">';
        echo '<h1>Welcome to Winlorant</h1>';
        echo '<p>Winlorant is a simple tool that allows you to track lifetime stats like your winrate or headshots per day and your winrate per map.</p>';
        echo '<p>Start by register / login to start tracking your ranked stats.</p>';
        echo "<p>Once you've logged (for 1 month, after you'll need to log in again), if it's the first time, we will scrap your 7 past days games</p>";
        echo "<p>Then we will update your data each day automatically.</p>";
        echo "<p>Hope you will enjoy this small project!</p>";
        echo '</div>';
    }
    ?>
</body>

</html>