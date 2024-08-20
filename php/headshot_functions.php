<?php

include_once "get_data_json.php";
include_once "data_functions.php";

function get_hits(array $data): array
{
    $headshot = $data["stats"]["shots"]["head"];
    $other = (intval($data["stats"]["shots"]["body"])
        + intval($data["stats"]["shots"]["leg"]));
    return [$headshot, $headshot + $other];
}

function get_headshot_per_day(?int $oldest = null, ?int $newest = null): array
{
    $headshot_per_day = array(
        "Monday" => [0, 0],
        "Tuesday" => [0, 0],
        "Wednesday" => [0, 0],
        "Thursday" => [0, 0],
        "Friday" => [0, 0],
        "Saturday" => [0, 0],
        "Sunday" => [0, 0]
    );

    foreach (GAME_JSON["data"] as $key) {
        if (!is_competitive($key) && !is_unrated($key)) {
            continue;
        }

        $date = $key["meta"]["started_at"];
        $date = strtotime($date);

        if (($oldest !== null && $date < $oldest) || ($newest !== null && $date > $newest)) {
            continue;
        }

        $day = get_day_by_timestamp($date);
        $hits = get_hits($key);
        $headshot_per_day[$day][0] += $hits[0];
        $headshot_per_day[$day][1] += $hits[1];
    }

    $total_headshot = 0;
    $total_hits = 0;

    foreach ($headshot_per_day as $key => $value) {
        if ($value[1] === 0) {
            $headshot_per_day[$key] = "No games played";
        } else {
            $headshot_rate = $value[0] / $value[1] * 100;
            $headshot_per_day[$key] = $headshot_rate;
            $total_headshot += $value[0];
            $total_hits += $value[1];
        }
    }

    if ($total_hits !== 0) {
        $average_headshot_rate = $total_headshot / $total_hits * 100;
    } else {
        $average_headshot_rate = "No games played";
    }

    $headshot_per_day["Global"] = $average_headshot_rate;

    return $headshot_per_day;
}
