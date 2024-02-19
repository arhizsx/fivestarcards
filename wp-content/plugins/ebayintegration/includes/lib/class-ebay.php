<?php 
	//  public function create_ebay_enpoint( ){

	// 	register_rest_route( '/ebayintegration/v1', '/request', array(
	// 		'methods' => 'GET',
	// 		'callback' => array( $this, 'handle_api_endpoint' )
	// 	) );        

    // }

    public $access_token;		
	public $refresh_token;

function handle_api_endpoint($data){

$executed = false;
$max_retry = 5;
$retries = 0;
$result = "";

while($executed == false){

    $retries++;
    $result = $this->getItems();

    if($result["Ack"] == "Success"){
        $executed = true;
    } 
    elseif($result["Ack"] == "Failure"){
        $this->refreshToken();
    }

    if($retries == $max_retry){
        $executed = true;
        $result = "Max Retries";
    }
}

return $result;
}

function refreshToken(){
return true;
}

function getItems($page_number = null){

$apiURL = "https://api.ebay.com/ws/api.dll";
$per_page = 100;
$page_number = 1;

if( $page_number != null ){
    $page_number = $page_number ;
}

$post_data = 
'<?xml version="1.0" encoding="utf-8"?>' .
'<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
'<RequesterCredentials>' .
  '<eBayAuthToken>' . $this->access_token . '</eBayAuthToken>' .
    '</RequesterCredentials>' .
  '<ErrorLanguage>en_US</ErrorLanguage>' .
    '<WarningLevel>High</WarningLevel>' .
    '<ActiveList>' .
  '<Sort>TimeLeft</Sort>' .
    '<Pagination>' .
    '<EntriesPerPage>' . $per_page . '</EntriesPerPage>' .
      '<PageNumber>' . $page_number . '</PageNumber>' .
      '</Pagination>' .
    '</ActiveList>' .
'</GetMyeBaySellingRequest> ';

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
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$post_data,
        CURLOPT_HTTPHEADER => [
            'X-EBAY-API-SITEID:0',
            'X-EBAY-API-COMPATIBILITY-LEVEL:967',
            'X-EBAY-API-CALL-NAME:GetMyeBaySelling',
        ]
    ]
);

$response = curl_exec($curl);
$status = curl_getinfo($curl);

curl_close($curl);

$xml=simplexml_load_string($response) or die("Error: Cannot create object");
$json = json_decode(json_encode($xml), true);


return $json;

}


?>