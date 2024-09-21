<?php

define("API_ACCOUNT", "https://api.henrikdev.xyz/valorant/v1/account/");

function get_user_region(string $name, string $tag): ?string
{
    include __DIR__ . '/db.php';
    $url_with_key = API_ACCOUNT . rawurlencode($name) . "/" . rawurlencode($tag) . "?api_key=" . $api_key;
    $curl_handle = curl_init();

    // Options cURL
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl_handle, CURLOPT_URL, $url_with_key);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));

    $response = curl_exec($curl_handle);

    if (curl_errno($curl_handle)) {
        return null;
    }

    curl_close($curl_handle);
    $data = json_decode($response, true);
    if (isset($data["data"]['region'])) {
        return $data["data"]['region'];
    }
    return null;
}
