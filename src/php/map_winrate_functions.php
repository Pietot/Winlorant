<?php

include_once "get_data_json.php";
include_once "data_functions.php";
include_once "winrate_functions.php";

function get_map_winrate(?int $oldest = null, ?int $newest = null): array
{
    $win_per_map = array();
    $game_json = get_json();

    foreach ($game_json["d"] as $key) {
        $date = $key["mt"]["st"];
        $date = strtotime($date);

        if (($oldest !== null && $date < $oldest) || ($newest !== null && $date > $newest)) {
            continue;
        }

        $map = $key["mt"]["mp"];
        if (!array_key_exists($map, $win_per_map)) {
            $win_per_map[$map] = [0, 0];
        }

        $win_per_map[$map][0] += has_win($key);
        $win_per_map[$map][1]++;
    }

    foreach ($win_per_map as $map => $value) {
        $winrate = $value[0] / $value[1];
        $win_per_map[$map] = $winrate;
    }

    arsort($win_per_map);

    return $win_per_map;
}
