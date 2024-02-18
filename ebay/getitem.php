<?php 

$access_token = $_GET["access_token"];
$apiURL = "https://api.ebay.com/buy/browse/v1/item/get_item_by_legacy_id?legacy_item_id=" . $_GET["item_id"];
$post_data = "";

$curl = curl_init();

curl_setopt_array(
    $curl,
    [
        CURLOPT_URL => $apiURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $access_token,
        ]
    ]
);

$response = curl_exec($curl);
$status = curl_getinfo($curl);

curl_close($curl);

print_r($response);


?>