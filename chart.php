<?php

include_once "request.php";

$name = "Plouf VoltaniX";
$tag = "0000";

$game_json = get_request_json($name, $tag);



// Competitive of unrated
function get_number_game()
{
    global $game_json;
    $nb_game = 0;
    foreach ($game_json["data"] as $key) 
    {
        $mode = $key["meta"]["mode"];
        if ($mode == "Competitive" || $mode == "Unrated") 
        { 
            $nb_game += 1;
        }
    }
    return $nb_game;
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

function get_winrate_per_day(string $name, string $tag, ?int $oldest = null, ?int $newest = null): array
{
    global $game_json;
    
    $win_per_day = array(
        "Monday" => [0, 0],
        "Tuesday" => [0, 0],
        "Wednesday" => [0, 0],
        "Thursday" => [0, 0],
        "Friday" => [0, 0],
        "Saturday" => [0, 0],
        "Sunday" => [0, 0]
    );

    
    foreach ($game_json["data"] as $key) {
        $mode = $key["meta"]["mode"];
        if ($mode != "Competitive" && $mode != "Unrated") { 
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
        } 
        else {
            $win_per_day[$day] = round($value[0] / $value[1] * 100) . "%";
        }
    }

    return $win_per_day;
}

echo "<h2>" . get_number_game() . " parties ont été analysé. Ce nombre sera actualisé automatiquement tous les dimanches.</h2><br>";

print_r(get_winrate_per_day($name, $tag));
