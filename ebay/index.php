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

$headers = array (
    'Authorization' => 'Basic ' . base64_encode($client_id . ":" . $client_secret),
    'Content-Type'  => 'application/x-www-form-urlencoded'
);

$post_data = json_encode(array(
        'grant_type' => 'authorization_code',
        'code' => $auth_code,
        'redirect_uri' => 'Fernando_Salvad-Fernando-5starc-qxmeny'
));


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiURL);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);

print_r($response);


?>

<script>

</script>

