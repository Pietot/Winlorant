<?php

function is_registered(PDO $db): ?array
{
    $username = $_COOKIE['username'] ?? false;
    $tag = $_COOKIE['tag'] ?? false;
    $region = $_COOKIE['region'] ?? false;

    if ($username && $tag && $region) {
        $query = "SELECT * FROM users WHERE username = :username AND tag = :tag AND region = :region";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
        $stmt->bindValue(':region', $region, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return [$username, $tag, $region];
        }
    }
    return null;
}
