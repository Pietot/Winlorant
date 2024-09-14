<?php

include_once "headshot_functions.php";

function get_headshot_json()
{
    $headshots = get_headshot_per_day();
    $headshots_json = json_encode($headshots);
    return $headshots_json;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo get_headshot_json();
}
