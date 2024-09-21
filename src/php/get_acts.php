<?php

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
