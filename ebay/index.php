<?php 
$auth_code = urldecode($_GET["code"]);

// PRODUCTION
$client_id = "Fernando-5starcar-PRD-a81fdd189-a762dfc7";
$client_secret = "PRD-81fdd189cbf1-f472-4131-93a2-1990";
$redirect_uri  = "Fernando_Salvad-Fernando-5starc-qxmeny";
$apiURL = "https://api.ebay.com/identity/v1/oauth2/token";
$grant_type = "authorization_code";

echo $auth_code;
echo "<hr>";
echo 'Basic ' . base64_encode($client_id . ":" . $client_secret);
echo "<hr>";

$post_data = [
        'grant_type' => 'authorization_code',
        'code' => $auth_code,
        'redirect_uri' => 'Fernando_Salvad-Fernando-5starc-qxmeny'
];

$curl = curl_init();

curl_setopt_array(
    $curl,
    [
        CURLOPT_URL => 'https://api.ebay.com/identity/v1/oauth2/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query($post_data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . base64_encode($client_id . ":" . $client_secret)
        ]
    ]
);

$response = curl_exec($curl);
$status = curl_getinfo($curl);

curl_close($curl);

$results = json_decode($response, true);

?>

<script>

</script>

