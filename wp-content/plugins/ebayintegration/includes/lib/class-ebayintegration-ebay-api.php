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

//  require_once "../../../../wp-load.php";

class Ebay_Integration_Ebay_API {

    public $access_token;		
	public $refresh_token;
    public $authorization;		
    public $content_type;		
    public $per_page;	
	public $wpdb;	

	
	public function __construct( ) {

		global $wpdb;

		$this->wpdb = $wpdb;

		$this->access_token = get_option("wpt_access_token");
		$this->refresh_token = get_option("wpt_refresh_token");
		$this->authorization = get_option("wpt_authorization");
		$this->content_type = "application/x-www-form-urlencoded";
		$this->per_page = 200;

        add_action("rest_api_init", array($this, 'create_ebay_enpoint'));

		add_shortcode('ebayintegration-shortcode', array( $this, 'shortcode' ));
		
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
			return array("error"=> true, "error_message" => "Action Not Set");
		}

		if($params["action"] == ""){
			return array("error"=> true, "error_message" => "Action Not Defined");
		} 

		elseif($params["action"] == "getItems"){

			if( isset( $params["page_number"] ) ){
				$page_number = $params["page_number"];
			} else {
				$page_number = 1;
			}

			return $this->getItems($page_number);
		} 

		elseif($params["action"] == "getItemPages"){
			
			$pages = $this->GetItemPages();
			
			return $this->getItemsMulti( $pages["data"], 200);


		} 

		elseif($params["action"] == "getItemInfo"){

			return $this->getItemInfo($params["item_id"]);

		} 

		elseif($params["action"] == "refreshToken"){

			return $this->refreshToken();

		} 

		elseif($params["action"] == "confirmAddSKU"){

			$user_id =  $params["user_id"];
			$meta = "sku";

			$skus = get_user_meta( $user_id, "sku", true );

			if($skus != "" ){

				if( in_array( $params["sku"], $skus ) == false ){
					array_push($skus, $params["sku"]);
				}
	
				update_user_meta( $user_id, $meta, $skus);	

			} else {

				$value = array ( $params["sku"] );  
				add_user_meta( $user_id, $meta, $value);					
			}


			$skus = get_user_meta( $user_id, "sku", true );


			return array("error"=> false, "skus" => $skus );

		} 

		elseif($params["action"] == "removeSKU"){


			$skus = get_user_meta( $params["user_id"], "sku", true );

			$new_skus = [];

			foreach( $skus as $sku ){

				if($sku != $params["sku"]){
					array_push( $new_skus, $sku );
				}

			}

			update_user_meta(  $params["user_id"], "sku", $new_skus);	

			return array("error"=> false, "skus" => $new_skus );


		}
		else {
			return array("error"=> true, "error_message" => $params["action"] . " - Action Not Defined");
		}
	
	}

	public function refreshToken(){

		$apiURL = "https://api.ebay.com/identity/v1/oauth2/token";

		$post_data = [
			"grant_type" => "refresh_token",
			"refresh_token" => $this->refresh_token,
		];

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
				CURLOPT_POSTFIELDS => http_build_query($post_data),
				CURLOPT_HTTPHEADER => [
					'Content-Type: application/x-www-form-urlencoded',
					'Authorization: ' .  $this->authorization
				]
			]
		);
		
		$response = curl_exec($curl);
		$status = curl_getinfo($curl);
		
		curl_close($curl);

		$json = json_decode($response, true);
		update_option("wpt_access_token", $json["access_token"]);

		return $json;
	}
	
	public function GetItemPages(){

		$per_page = 2;
		$page_number = 1;

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
		
		if(array_key_exists( "Ack", $json )){

			if($json["Ack"] == "Failure"){

				if( $json["Errors"]["ShortMessage"] == "Auth token is hard expired." ){
					
					return array("error" => true, "data"=> "Refresh Access Token");

				} else {

					return array("error" => true, "data"=> $json);

				}
	
			} else {
				
				if( count($json["ActiveList"]["ItemArray"]["Item"]) == 2){
	
					$entries = $json["ActiveList"]["PaginationResult"]["TotalNumberOfEntries"];
					$pages = ceil($entries / $this->per_page);
	
					return array("error" => false, "data"=> $pages);
					return $pages;
					
				} else {

					return array("error" => true, "data"=> $json);
				}
			}
	
		} else {
			return "Not Valid JSON";
		}

	}				

	public function getItems($page_number = null,  $per_page = null){


		$apiURL = "https://api.ebay.com/ws/api.dll";
		
		if($page_number == null){
			$page_number = 1;
		}

		if($per_page == null){
			$per_page = $this->per_page;
		}

		$post_data = 
		'<?xml version="1.0" encoding="utf-8"?>' .
		'<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
		'<RequesterCredentials>' .
		  '<eBayAuthToken>' . get_option("wpt_access_token")  . '</eBayAuthToken>' .
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
		
		if(array_key_exists( "Ack", $json )){

			if($json["Ack"] == "Failure"){

				if( $json["Errors"]["ShortMessage"] == "Auth token is hard expired." ){
					
					return array("error" => true, "data"=> "Refresh Access Token");

				} else {

					return array("error" => true, "data"=> $json);

				}
	
			} else {

				foreach($json["ActiveList"]["ItemArray"]["Item"] as $item){

					$this->wpdb->replace("ebay", array(
						"item_id" => $item["ItemID"],
						"sku" => $item["SKU"],
						"data" => json_encode($item),
						"status" => "active"
					));
				}
		
				

				return array("error" => false, "page" => $page_number, "data"=> $json, "items" => $json["ActiveList"]["ItemArray"]["Item"] );
				
			}
	
		} else {
			return "Not Valid JSON";
		}
		
	}
		
	public function getItemsMulti($pages = null,  $per_page = null){

		$apiURL = "https://api.ebay.com/ws/api.dll";


		$multiCurl = array();

		$result = array();

		$mh = curl_multi_init();

		// Setup Multi Curl Requests

		for( $i = 1; $i <= $pages; $i++ ){

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
						'<PageNumber>' . $i . '</PageNumber>' .
						'</Pagination>' .
					'</ActiveList>' .
				'</GetMyeBaySellingRequest> ';


			$multiCurl[$i] = curl_init();			

			curl_setopt_array(
				$multiCurl[$i],
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

			curl_multi_add_handle($mh, $multiCurl[$i]);

		}

		$index = null;

		// Execute Multi Curl
		do {

			curl_multi_exec( $mh, $index );

		} 
		while($index > 0);		

		// Save Result of Each Curl Pass
		foreach($multiCurl as $k => $ch) {

			$result[$k] = curl_multi_getcontent($ch);
			curl_multi_remove_handle($mh, $ch);

		}

		// Close Multi Curl
		curl_multi_close($mh);		


		// Get All Items From All Results
		$items = array();

		foreach( $result as $k => $v ){

			$xml=simplexml_load_string( $result[$k] ) or die("Error: Cannot create object");
			$json = json_decode(json_encode($xml), true);
			
			if(array_key_exists( "Ack", $json )){
	
				if($json["Ack"] == "Failure"){
	
					if( $json["Errors"]["ShortMessage"] == "Auth token is hard expired." ){
						
						return array("error" => true, "data"=> "Refresh Access Token");
	
					} else {
	
						return array("error" => true, "data"=> $json);
	
					}
		
				} else {
	
					foreach( $json["ActiveList"]["ItemArray"]["Item"] as $item ){
						array_push( $items, $item );
					}

					
				}
		
			} else {
				return "Not Valid JSON";
			}
	
		}

		// Get All User SKUs

		$users_with_sku = $this->wpdb->get_results ( "
			SELECT user_id 
			FROM  wp_usermeta
			WHERE meta_key = 'sku'
		" );

		$all_skus = array();

		foreach($users_with_sku as $user){
			$skus = get_user_meta( $user->user_id, "sku", true );		
			array_push( $all_skus, ...$skus );
		}

		
		// Remove Items With SKUs

		$undefined_items = array();
		foreach($items as $item){
			if( ! in_array( $item["SKU"], $all_skus ) ) {
				array_push( $undefined_items, $item );
			}
		}



		return ["count" => count( $items ), "items" => $items, "active_skus" => $all_skus, "undefined_items" => $undefined_items ];


	}	



	public function getItemInfo($item_id){

		$apiURL = "https://api.ebay.com/ws/api.dll";
		
		$post_data = 
		'<?xml version="1.0" encoding="utf-8"?>' .
		'<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
			'<ErrorLanguage>en_US</ErrorLanguage>' .
			'<WarningLevel>High</WarningLevel>' .
		  '<ItemID>' . $item_id .'</ItemID>' .
		'</GetItemRequest>';
				
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
					'X-EBAY-API-CALL-NAME:GetItem',
					'X-EBAY-API-IAF-TOKEN::' . get_option("wpt_access_token")
				]
			]
		);
		
		$response = curl_exec($curl);
		$status = curl_getinfo($curl);
		
		curl_close($curl);
		
		$xml=simplexml_load_string($response) or die("Error: Cannot create object");
		$json = json_decode(json_encode($xml), true);
		
		if(array_key_exists( "Ack", $json )){

			if($json["Ack"] == "Failure"){

				if( $json["Errors"]["ShortMessage"] == "Auth token is hard expired." ){
					
					return array("error" => true, "data"=> "Refresh Access Token");

				} else {

					return array("error" => true, "data"=> $json);

				}
	
			} else {

				return array("error" => false, "data"=> $json);
				
			}
	
		} else {
			return "Not Valid JSON";
		}
		
	}

    public function shortcode($atts) 
    {

        $default = array(
            'title' => 'Shortcode',
            'type' => ''
        );
        
        $params = shortcode_atts($default, $atts);


        ob_start();

		include( plugin_dir_path( __FILE__ ) . 'shortcodes/router.php');			
		
        $output = ob_get_clean(); 
        
        return $output ;
    }
	

}