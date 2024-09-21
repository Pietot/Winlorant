<?php

include_once "winrate_functions.php";

function get_winrate_json(?string $act = null): string
{
    $winrate = get_winrate_per_day(act: $act);
    $winrate_json = json_encode($winrate);
    return $winrate_json;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $act = isset($_GET['act']) && $_GET['act'] !== 'null' ? $_GET['act'] : null;
    echo get_winrate_json($act);
}
