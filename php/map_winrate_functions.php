<?php

include_once "get_data_json.php";
include_once "data_functions.php";
include_once "winrate_functions.php";

function get_map_winrate(?int $oldest = null, ?int $newest = null): array
{
    $win_per_map = array();

    $totalWins = 0;
    $totalGames = 0;

    foreach (GAME_JSON["data"] as $key) {
        if (!is_competitive($key) && !is_unrated($key)) {
            continue;
        }

        $date = $key["meta"]["started_at"];
        $date = strtotime($date);

        if (($oldest !== null && $date < $oldest) || ($newest !== null && $date > $newest)) {
            continue;
        }

        $map = $key["meta"]["map"]["name"];
        if (!array_key_exists($map, $win_per_map)) {
            $win_per_map[$map] = [0, 0];
        }

        $win_per_map[$map][0] += has_win($key);
        $win_per_map[$map][1]++;
    }

    foreach ($win_per_map as $map => $value) {
        $winrate = $value[0] / $value[1] * 100;
        $win_per_map[$map] = $winrate;
        $totalWins += $value[0];
        $totalGames += $value[1];
    }

    arsort($win_per_map);
    $average_winrate = $totalWins / $totalGames * 100;
    $win_per_map["Global"] = $average_winrate;

    return $win_per_map;
}
