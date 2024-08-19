<?php

include_once "headshot_functions.php";

function get_headshot_json()
{
    $winrate = get_winrate_per_day();
    $winrate_json = json_encode($winrate);
    return $winrate_json;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo get_headshot_json();
}
