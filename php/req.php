<?php

define("API_KEY", "HDEV-284f2d01-91dd-40a7-b52b-5ae7dbbe1309");
define("API_URL", "https://api.henrikdev.xyz/valorant/v1/stored-matches/eu/");

$NAME = "Plouf VoltaniX";
$TAG = "9168";

function getRequestJson(string $name, string $tag): ?array
{
    $urlWithKey = API_URL . rawurlencode($name) . "/" . rawurlencode($tag) . "?api_key=" . API_KEY;
    $response = file_get_contents($urlWithKey);

    if ($response === FALSE) {
        die("Erreur lors de la requête à l'API");
    }

    return json_decode($response, true);
}

function getDayByDate($date_iso) {
    $dateObj = new DateTime($date_iso);
    $day = $dateObj->format('l');

    return $day;
}


$gameJson = getRequestJson($NAME, $TAG);
$nbOfGame = $gameJson["results"]["total"];
echo "<h1>" . $nbOfGame . " parties ont été analysés. L'actualisation a lieu tous les dimanches </h1><br><br>";

foreach ($gameJson["data"] as $key) {
    
    $scoreRed = $key["teams"]["red"];
    $scoreBlue = $key["teams"]["blue"];
    echo $scoreBlue . " - " . $scoreRed . " ";
    $date = $key["meta"]["started_at"];
    echo "Day: " . getDayByDate($date) . "<br>";
}

$winArray = [];
$headShotArray = [];
$bodyShotArray = [];
$legShotArray = [];

function setUpArray($gameJson, &$winArray, &$headShotArray, &$bodyShotArray, &$legShotArray) {
    
    foreach ($gameJson["data"] as $key) {
        #$winArray[] = 
        $headShotArray[] = $key["stats"]["shots"]["head"];
        $bodyShotArray[] = $key["stats"]["shots"]["body"];
        $legShotArray[] = $key["stats"]["shots"]["leg"];

    }
}

setUpArray($gameJson, $headShotArray, $bodyShotArray, $legShotArray);

print_r($headShotArray);
print_r($bodyShotArray);
print_r($legShotArray);