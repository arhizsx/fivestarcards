<?php

$apiURL = "https://api.ebay.com/identity/v1/oauth2/token";

$post_data = [
    "grant_type" => "refresh_token",
    "refresh_token" => $this->refresh_token,
];

$curl = curl_init();

// Set cURL options
curl_setopt($curl, CURLOPT_URL, "https://www.google.com");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($curl);

// Check if an error occurred
if (curl_errno($curl)) {
    // Get the error message
    $error_msg = curl_error($curl);
    return  "cURL error: " . $error_msg;
} else {
    // Process the response
    return "Response: " . $response;
}

// Close cURL resource
curl_close($curl);


?>