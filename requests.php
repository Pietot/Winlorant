<?php

define("API_KEY", "HDEV-284f2d01-91dd-40a7-b52b-5ae7dbbe1309");

$NAME = "Plouf VoltaniX";
$TAG = "9168";

if (function_exists('curl_version')) {
    echo 'cURL is installed and enabled';
} else {
    echo 'cURL is not installed or enabled';
}

function get_request_json($url)
{
    $curl_handle = curl_init();

    // Options cURL
    curl_setopt($curl_handle, CURLOPT_URL, $url . '?api_key=' . API_KEY);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'

    ));

    // Exécution de la requête
    $response = curl_exec($curl_handle);

    // Vérification des erreurs
    if (curl_errno($curl_handle)) {
        echo 'Erreur cURL : ' . curl_error($curl_handle);
    }
    curl_close($curl_handle);

    $data = json_decode($response, true);

    return $data;
}

echo get_request_json("https://api.henrikdev.xyz/valorant/v1/stored-matches/eu/" . $NAME . "/" . $TAG);
