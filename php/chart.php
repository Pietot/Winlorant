<?php

include_once "get_data_json.php";

$name = "Plouf VoltaniX";
$tag = "0000";

define("GAME_JSON", get_data_json($name, $tag));

function get_number_game()
{
    $nb_game = 0;
    foreach (GAME_JSON["data"] as $key) {
        if (is_competitive($key) || is_unrated($key)) {
            $nb_game += 1;
        }
    }
    return $nb_game;
}

function is_competitive(array $data): bool
{
    $mode = $data["meta"]["mode"];
    if ($mode === "Competitive") {
        return true;
    }
    return false;
}

function is_unrated(array $data): bool
{
    $mode = $data["meta"]["mode"];
    if ($mode === "Unrated") {
        return true;
    }
    return false;
}

function get_day_by_timestamp($timestamp)
{
    $date_obj = new DateTime();
    $date_obj->setTimestamp($timestamp);
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

function get_winrate_per_day(?int $oldest = null, ?int $newest = null): array
{
    $win_per_day = array(
        "Monday" => [0, 0],
        "Tuesday" => [0, 0],
        "Wednesday" => [0, 0],
        "Thursday" => [0, 0],
        "Friday" => [0, 0],
        "Saturday" => [0, 0],
        "Sunday" => [0, 0],
    );

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

        $day = get_day_by_timestamp($date);
        $win_per_day[$day][0] += has_win($key);
        $win_per_day[$day][1]++;
    }

    foreach ($win_per_day as $day => $value) {
        if ($value[1] === 0) {
            $win_per_day[$day] = "No games played";
        } else {
            $winrate = ($value[0] / $value[1] * 100);
            $win_per_day[$day] = $winrate . "%";

            $totalWins += $value[0];
            $totalGames += $value[1];
        }
    }

    if ($totalGames > 0) {
        $averageWinrate = ($totalWins / $totalGames * 100) . "%";
    } else {
        $averageWinrate = "No games played";
    }

    $win_per_day["Global"] = $averageWinrate;

    return $win_per_day;
}
