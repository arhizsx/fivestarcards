<?php

$apiURL = "https://api.ebay.com/identity/v1/oauth2/token";

$post_data = [
    "grant_type" => "refresh_token",
    "refresh_token" => $this->refresh_token,
];

$curl = curl_init();

var_dump($curl);



?>