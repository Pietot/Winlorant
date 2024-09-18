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
    <title>About</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" />
    <link rel="stylesheet" type="text/css" href="css/about.css" />
</head>

<body>
    <header class="header">
        <div class="header-links">
            <a class="home" href="index.php">Winlorant Tracker</a>
        </div>
        <div class="header-links">
            <a class="about" href="about.php">About</a>
        </div>
        <div class="header-links">
            <a class="contact" href="contact.html">Contact</a>
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
    <div class="text-container">
        <h2>About the project</h2>
        <p>Winlorant Tracker is a web application that allows you to track your winrate in Valorant. It uses the <a href="https://github.com/Henrik-3/unofficial-valorant-api" target="_blank">unofficial Henrik-3 API</a> to get your match history and calculate your winrate.</p>
        <h2>How to use</h2>
        <p>First, you need to register with your Valorant username and tag. The application will then fetch your match history from the past 7 days and process the data to show it into beautiful chart.</p>
        <h2>How it works</h2>
        <p>After this, all the data will be stored into the database and every day at ~00:00 UTC, the application will fetch your match history again and update the chart with the new data.</p>
        <h2>How can I contribute?</h2>
        <p>If you have any suggestion and/or want to contribute to the project, you can check the <a href="contact.php">contact</a> page to get in touch with me.</p>


</body>

</html>