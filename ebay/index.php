<?php 
$auth_code = urldecode($_GET["code"]);

// PRODUCTION
$client_id = "Fernando-5starcar-PRD-a81fdd189-a762dfc7";
$client_secret = "PRD-81fdd189cbf1-f472-4131-93a2-1990";
$redirect_uri  = "Fernando_Salvad-Fernando-5starc-qxmeny";
$apiURL = "https://api.ebay.com/identity/v1/oauth2/token";
$grant_type = "authorization_code";
$authorization = 'Basic ' . base64_encode($client_id . ":" . $client_secret);

echo '<div class="ebay" 
    data-code="' . $auth_code . '" 
    data-client_id="' . $client_id . '" 
    data-client_secret="' . $client_secret . '" 
    data-redirect_uri="' . $redirect_uri . '" 
    data-apiURL="' . $apiURL . '" 
    data-grant_type="' . $grant_type . '" 
    data-authorization="' . $authorization . '"'. 
    '><eBay Integration/div>';

?>

<script>

</script>

