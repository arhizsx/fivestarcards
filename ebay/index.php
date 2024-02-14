<?php 
$auth_code = urldecode($_GET["code"]);

echo $auth_code;

echo "<hr>";



$client_id = "Fernando-5starcar-PRD-a81fdd189-a762dfc7";
$client_secret = "PRD-81fdd189cbf1-f472-4131-93a2-1990";
$redirect_uri  = "Fernando_Salvad-Fernando-5starc-qxmeny";

$headers = array (
    'Authorization' => print('Basic ' . base64_encode($client_id . ":" . $client_secret)),
    'Content-Type'  => 'application/x-www-form-urlencoded'
);

echo 'Basic ' . base64_encode($client_id . ":" . $client_secret);

echo "<hr>";


$apiURL = "https://api.ebay.com/identity/v1/oauth2/token";
$urlParams = array (
    "grant_type" => "client_credentials",
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

