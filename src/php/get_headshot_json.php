<?php

include_once "headshot_functions.php";

function get_headshot_json(?string $act = null): string
{
    $headshots = get_headshot_per_day(act: $act);
    $headshots_json = json_encode($headshots);
    return $headshots_json;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $act = isset($_GET['act']) && $_GET['act'] !== 'null' ? $_GET['act'] : null;
    echo get_headshot_json($act);
}
