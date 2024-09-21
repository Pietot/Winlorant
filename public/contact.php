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
    <div class="text-container">
        <h2>Github</h2>
        <p>If you want to look at the source code or improve directly the project, you can check the <a href="https://github.com/Pietot/Winrate-tracker" target="_blank">GitHub repository</a>.</p>
        <h2>Contact</h2>
        <p>If you have any suggestion and/or want to contribute indirectly to the project, you can contact me by discord by sending me a friend request at Pietot (pm openned)</p>
    </div>