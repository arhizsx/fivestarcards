<?php
/**
 * .
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

    public $access_token;		
	public $refresh_token;
    public $authorization;		
    public $content_type;		

	
	public function __construct( ) {

		$this->access_token = "v^1.1#i^1#r^0#f^0#p^3#I^3#t^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";
		$this->refresh_token = "v^1.1#i^1#r^1#p^3#I^3#f^0#t^Ul4xMF83OkZFMDQ5NTk4NkI0MzgyRjVFQTY2QzQ3NEIxNDY0RkMxXzBfMSNFXjI2MA==";
		$this->authorization = "Basic RmVybmFuZG8tNXN0YXJjYXItUFJELWE4MWZkZDE4OS1hNzYyZGZjNzpQUkQtODFmZGQxODljYmYxLWY0NzItNDEzMS05M2EyLTE5OTA=";
		$this->content_type = "application/x-www-form-urlencoded";

        add_action("rest_api_init", array($this, 'create_ebay_enpoint'));

	}

	 public function create_ebay_enpoint( ){

		register_rest_route( '/ebayintegration/v1', '/ajax', array(
			'methods' => 'GET',
			'callback' => array( $this, 'handle_api_endpoint' )
		) );        

    }

	public function handle_api_endpoint($data){

        $headers = $data->get_headers();
        $params = $data->get_params();
        $nonce = $headers["x_wp_nonce"][0];

		if( !isset($params["action"]) ){
			return "Action Not Set";
		}

		if($params["action"] == "getItems"){
			return $this->handleGetItems();

		} else {
			return $params["action"] . " - Action Not Defined";
		}
	
	}

	public function refreshToken(){
		return true;
	}
				
	public function handleGetItems(){

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
				$result = $this->refreshToken();
			}
		
			if($retries == $max_retry){
				$executed = true;
				$result = "Max Retries";
			}
		}


		if( count($result["ActiveList"]["ItemArray"]["Item"]) == 2){

			$entries = $result["ActiveList"]["PaginationResult"]["TotalNumberOfEntries"];
			$pages = ceil($entries / 100);

			$items = [];

			for($i = 1; $i <= $pages; $i++ ){
				$loop_result =  $this->getItems($i, 100);
				foreach($loop_result["ActiveList"]["ItemArray"]["Item"] as $item){
					$items[] = $item; 
				}
			}

			return $items;
			
		} else {
			return $result;
		}


	}

	public function getItems($page_number = 1,  $per_page = 2){
		
		$apiURL = "https://api.ebay.com/ws/api.dll";
		
		$post_data = 
		'<?xml version="1.0" encoding="utf-8"?>' .
		'<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
		'<RequesterCredentials>' .
		  '<eBayAuthToken>' . $this->access_token  . '</eBayAuthToken>' .
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