<?php

include_once "map_winrate_functions.php";

function get_winrate_json()
{
    $map_winrate = get_map_winrate();
    $map_winrate_json = json_encode($map_winrate);
    return $map_winrate_json;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo get_winrate_json();
}
