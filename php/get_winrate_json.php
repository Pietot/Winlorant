<?php

include_once "chart.php";

function get_winrate_json()
{
    $winrate = get_winrate_per_day();
    $winrate_json = json_encode($winrate);
    return $winrate_json;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo get_winrate_json();
}