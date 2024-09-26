<?php

include_once "data_functions.php";

function get_id(): int
{
    include "db.php";
    $query = "SELECT id FROM users WHERE username = :username AND tag = :tag";
    $stmt = $db->prepare($query);
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->bindValue(':tag', $_SESSION['tag'], PDO::PARAM_STR);
    $stmt->execute();
    $id = $stmt->fetch()['id'];
    return $id;
}

function get_json(): array
{
    $id = get_id();
    $json = file_get_contents(__DIR__ . "/../json/$id.json.gz");
    $json = gzdecode($json);
    $json = json_decode($json, true);
    return $json;
}

function get_number_game()
{
    $game_json = get_json();
    return count($game_json['dt']);
}

function has_win(array $data): bool
{
    $team = $data["sts"]["t"];
    $winning_team = ($data['ts']['b'] > $data['ts']['r']) ? "Blue" : "Red";
    if ($team === $winning_team) {
        return true;
    }
    return false;
}
;

function get_winrate_per_day(?int $oldest = null, ?int $newest = null, ?string $act = null): array
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
    $game_json = get_json();

    foreach ($game_json['dt'] as $key) {
        $date = $key["mt"]["st"];
        $date = strtotime($date);

        if (($oldest !== null && $date < $oldest) || ($newest !== null && $date > $newest) || ($act !== null && $key["mt"]["s"] !== $act)) {
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
            $winrate = $value[0] / $value[1];
            $win_per_day[$day] = $winrate;
            $totalWins += $value[0];
            $totalGames += $value[1];
        }
    }

    if ($totalGames > 0) {
        $averageWinrate = $totalWins / $totalGames;
    } else {
        $averageWinrate = "No games played";
    }

    $win_per_day["Global"] = $averageWinrate;

    return $win_per_day;
}
