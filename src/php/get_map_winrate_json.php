<?php

include_once "map_winrate_functions.php";

function get_map_winrate_json(?string $act = null): string
{
    $map_winrate = get_map_winrate(act: $act);
    $map_winrate_json = json_encode($map_winrate);
    return $map_winrate_json;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $act = isset($_GET['act']) && $_GET['act'] !== 'null' ? $_GET['act'] : null;
    echo get_map_winrate_json($act);
}
