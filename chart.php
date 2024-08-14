<?php

include_once "request.php";

$name = "Plouf VoltaniX";
$tag = "0000";

$game_json = get_request_json($name, $tag);

function get_day_by_date($date_iso)
{
    $date_obj = new DateTime($date_iso);
    $day = $date_obj->format('l');
    return $day;
}

function has_win(array $data): bool
{
    $team = $data["stats"]["team"];
    $winning_team = ($data['teams']['blue'] > $data['teams']['red']) ? "Blue" : "Red";
    if ($team === $winning_team) {
        return true;
    }
    return false;
};

function get_winrate_per_day(string $name, string $tag, string $oldest = "", string $newest = ""): array
{
    $win_per_day = array(
        "Monday" => [0, 0],
        "Tuesday" => [0, 0],
        "Wednesday" => [0, 0],
        "Thursday" => [0, 0],
        "Friday" => [0, 0],
        "Saturday" => [0, 0],
        "Sunday" => [0, 0]
    );

    $game_json = get_request_json($name, $tag);
    foreach ($game_json["data"] as $key) {
        $date = $key["meta"]["started_at"];
        if (($oldest !== "" && $date < $oldest) || ($newest !== "" && $date > $newest)) {
            continue;
        }
        $day = get_day_by_date($date);
        $win_per_day[$day][0] += has_win($key);
        $win_per_day[$day][1]++;
    }

    foreach ($win_per_day as $day => $value) {
        if ($value[1] === 0) {
            $win_per_day[$day] = "No games played";
        } else {
            $win_per_day[$day] = round($value[0] / $value[1] * 100, 2) . "%";
        }
    }

    return $win_per_day;
}

print_r(get_winrate_per_day($name, $tag));
