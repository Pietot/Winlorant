<?php

define("__API_KEY", "HDEV-284f2d01-91dd-40a7-b52b-5ae7dbbe1309");
define("__API_URL", "https://api.henrikdev.xyz/valorant/v1/content");

function get_acts(): array
{
    include_once __DIR__ . '/winrate_functions.php';
    $json = get_json();
    $acts = [];
    foreach ($json['d'] as $act) {
        if (!in_array($act['mt']['s'], $acts)) {
            array_push($acts, $act['mt']['s']);
        }
    }
    return $acts;
}
