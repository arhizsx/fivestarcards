<?php 
$auth_code = urldecode($_GET["code"]);

echo $auth_code;

echo "<hr>";


// PRODUCTION
// $client_id = "Fernando-5starcar-PRD-a81fdd189-a762dfc7";
// $client_secret = "PRD-81fdd189cbf1-f472-4131-93a2-1990";
// $redirect_uri  = "Fernando_Salvad-Fernando-5starc-qxmeny";
// $apiURL = "https://api.ebay.com/identity/v1/oauth2/token";

// SANDBOX
$client_id = "Fernando-5starcar-SBX-07f9b21e9-07eaeb73";
$client_secret = "SBX-7f9b21e930f4-69ef-46fe-92f6-0bce";
$redirect_uri  = "Fernando_Salvad-Fernando-5starc-xkabpeook";
$apiURL = "https://api.ebay.com/identity/v1/oauth2/token";
$grant_type = "authorization_code";


$headers = array (
    'Authorization' => print('Basic ' . base64_encode($client_id . ":" . $client_secret)),
    'Content-Type'  => 'application/x-www-form-urlencoded'
);

echo 'Basic ' . base64_encode($client_id . ":" . $client_secret);

echo "<hr>";



$urlParams = array (
    "grant_type" => $grant_type,
    "code" => $auth_code,
    "redirect_uri" => $redirect_uri
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Should be removed on production
curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_HEADER, 1 );

curl_setopt($ch, CURLOPT_URL, $apiURL);
curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $urlParams );

$resp = curl_exec ( $ch );
curl_close ( $ch );

echo "<hr>";

print_r ( $resp );
?>

