<?php

include "db.php";
include "get_data_json.php";
include "compress_json.php";

function get_users()
{
    global $db;
    $users = [];
    $sql = "SELECT * FROM users";
    $users = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

function update_json(): void
{
    $users = get_users();
    for ($i = 0; $i < count($users); $i++) {
        $user = $users[$i];
        $username = $user['username'];
        $tag = $user['tag'];
        $region = $user['region'];
        $user_json = get_data_json($username, $tag, $region);
    }
}

update_json();