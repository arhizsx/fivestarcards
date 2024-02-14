<?php 

echo  urldecode($_GET["code"]);
echo "<hr>";
echo "Basic " . base64_encode("<Fernando-5starcar-PRD-a81fdd189-a762dfc7>:<PRD-81fdd189cbf1-f472-4131-93a2-1990>");


  $curl = curl_init('https://api.sandbox.ebay.com/identity/v1/oauth2/token');
  curl_setopt($curl, CURLOPT_POST, true);
  $response = curl_exec($curl);
  curl_close($curl);
  print_r( $response );

?>

