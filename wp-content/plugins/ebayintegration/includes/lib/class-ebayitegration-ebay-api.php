<?php
/**
 * Settings class file.
 *
 * @package Ebay Integration/Includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings class.
 */
class Ebay_Integration_Ebay_API {

	public $access_token = "v^1.1#i^1#r^0#f^0#p^3#I^3#t^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";		
	public $refresh_token = "v^1.1#i^1#r^1#p^3#I^3#f^0#t^Ul4xMF83OkZFMDQ5NTk4NkI0MzgyRjVFQTY2QzQ3NEIxNDY0RkMxXzBfMSNFXjI2MA==";
	
	 public function __construct( ) {

        // add_action("rest_api_init", array($this, 'add_api_endpoint'));

	 }


	 public function create_ebay_enpoint(  ){

		register_rest_route( '/ebayintegration/v1', '/request', array(
			'methods' => 'GET',
			'callback' => array( $this, 'handle_api_endpoint' )
		) );        

    }

    public function handle_api_endpoint($data){

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

	public function refreshToken(){
		return true;
	}

	public function getItems($page_number = null){

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


     
}