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
        $id = $user['id'];
        $username = $user['username'];
        $tag = $user['tag'];
        $region = $user['region'];
        $user_json = get_data_json($username, $tag, $region);
        if (isset($user_json['errors'])) {
            if ($user_json['errors'][0]['message'] === "Rate limited") {
                $message = "Rate limit exceeded" . "Time: " . date("Y-m-d H:i:s") . "\n\n";
                file_put_contents(__DIR__ . "../../logs/logs.txt", $message, FILE_APPEND);
                sleep(10);
                $i--;
            } else {
                $message = "Error on user $username (id: $id):\n" . "Message:" . $user_json['errors'][0]['message'] . "\nDetails:" . $user_json['errors'][0]['details'] . "\nTime: " . date("Y-m-d H:i:s") . "\n\n";
                file_put_contents(__DIR__ . "/../../logs/logs.txt", $message, FILE_APPEND);
            }
        } else {
            $json = json_encode($user_json, 0);
            file_put_contents(__DIR__ . "/../json/$id.json.gz", gzencode($json));
            compress($id);
            $message = "User $username (id: $id) updated successfully" . " Time: " . date("Y-m-d H:i:s") . "\n";
            file_put_contents(__DIR__ . "/../../logs/logs.txt", $message, FILE_APPEND);
        }
    }
}

update_json();
