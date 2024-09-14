<?php
define("_API_KEY", "HDEV-284f2d01-91dd-40a7-b52b-5ae7dbbe1309");
define("_API_URL", "https://api.henrikdev.xyz/valorant/v1/account/");

function get_user_region(string $name, string $tag): ?string
{
    $url_with_key = _API_URL . rawurlencode($name) . "/" . rawurlencode($tag) . "?api_key=" . _API_KEY;
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
