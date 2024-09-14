<?php

include_once 'is_registered.php';
$database_config = include_once '../../src/config/database.php';

$host = $database_config['host'];
$dbname = $database_config['dbname'];
$user = $database_config['user'];
$password = $database_config['password'];

$db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
$userdata = is_registered($db);

if ($userdata) {
    unset($_COOKIE['username']); 
    setcookie('username', '', -1, '/'); 
    unset($_COOKIE['tag']);
    setcookie('tag', '', -1, '/');
    unset($_COOKIE['region']);
    setcookie('region', '', -1, '/');
}

header('Location: ../index.php');
