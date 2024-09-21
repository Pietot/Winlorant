<?php

include_once 'is_registered.php';
include 'db.php';

$userdata = is_registered($db);

if ($userdata) {
    unset($_COOKIE['username']); 
    setcookie('username', '', -1, '/'); 
    unset($_COOKIE['tag']);
    setcookie('tag', '', -1, '/');
    unset($_COOKIE['region']);
    setcookie('region', '', -1, '/');
}

header('Location: ../../public/index.php');
