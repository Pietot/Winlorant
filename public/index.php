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
    <title>Winlorant Tracker</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" />
</head>

<body>
    <header class="header">
        <div class="header-links">
            <a class="home" href="index.php">Winlorant Tracker</a>
        </div>
        <div class="header-links">
            <a class="about" href="about.html">About</a>
        </div>
        <div class="header-links">
            <a class="contact" href="contact.html">Contact</a>
        </div>
        <?php
        if ($userdata) {
            echo '<div class="header-links">' . "\n";
            echo '<p class="user" href="#">' . $_SESSION["username"] . '#' . $_SESSION["tag"] . "</p>\n";
            echo '</div>' . "\n";
            echo '<div class="header-links">' . "\n";
            echo '<a class="signout" href="../src/php/signout.php">Sign out</a>';
            echo '</div>' . "\n";
        } else {
            echo '<div class="header-links">' . "\n";
            echo '<a class="register" href="register.php">Register</a>';
            echo '</div>' . "\n";
        }
        ?>
    </header>
    <?php
    if ($userdata) {
        include_once '../src/php/winrate_functions.php';
        echo '<div class="text-container">' . "\n";
        echo '<p class="text-info">' . get_number_game($_SESSION['username'], $_SESSION['tag']) . ' games tracked. This number will be updated each day at ~00:00 UTC</p>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="chart-container">' . "\n";
        echo '<canvas id="dailyChart"></canvas>' . "\n";
        echo '</div>' . "\n";
        echo '<br>' . "\n";
        echo '<br>' . "\n";
        echo '<br>' . "\n";
        echo '<br>' . "\n";
        echo '<div class="chart-container">' . "\n";
        echo '<canvas id="mapChart"></canvas>' . "\n";
        echo '</div>' . "\n";
        echo '<br>' . "\n";
        echo '<br>' . "\n";
        echo '<br>' . "\n";
        echo '<br>' . "\n";
        echo '<script src="js/chart.min.js"></script>' . "\n";
        echo '<script src="js/chart_configuration.js"></script>' . "\n";
    } else {
        echo '<div class="welcome-container">' . "\n";
        echo '<h1>Welcome to Winlorant Tracker</h1>' . "\n";
        echo '<p>Winlorant Tracker is a simple tool that allows you to track lifetime stats like your winrate or headshots per day and your winrate per map.</p>' . "\n";
        echo '<p>Start by register / login to start tracking your ranked stats.</p>' . "\n";
        echo "<p>Once you've logged (for 1 month, after you'll need to log in again), if it's the first time, we will scrap your 7 past days games'</p>" . "\n";
        echo "<p>Then we will update your data each day automatically.</p>" . "\n";
        echo "<p>Hope you will enjoy this small project  ^^</p>" . "\n";
        echo '</div>' . "\n";
    }
    ?>
</body>

</html>