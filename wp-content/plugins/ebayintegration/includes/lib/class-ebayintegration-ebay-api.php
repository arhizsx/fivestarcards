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

			if($pages["error"] == false ){

				return $this->getItemsMulti( $pages["data"], 200);

			} else {
				return "Error getting item pages";
			}
			
		} 

		elseif($params["action"] == "getItemInfo"){

			return $this->getItemInfo($params["item_id"]);

		} 

		elseif($params["action"] == "getItemTransactions"){

			return $this->getItemTransactions($params["item_id"]);

		} 		

		elseif($params["action"] == "refreshTransactions"){

			return $this->refreshTransaction();

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

		elseif($params["action"] == "getEbayItems"){

			return $this->getEbayItems($params["type"], $params["page"], $params["days"]);

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
			'<SoldList>' .
				'<Include>true</Include>' .
				'<Pagination>' .
					'<EntriesPerPage>' . $per_page . '</EntriesPerPage>' .
			  		'<PageNumber>' . $page_number . '</PageNumber>' .
			  	'</Pagination>' .
				'<DurationInDays>60</DurationInDays>' .
			'</SoldList>' .
			'<UnsoldList>' .
				'<Include>true</Include>' .
				'<Pagination>' .
					'<EntriesPerPage>' . $per_page . '</EntriesPerPage>' .
			  		'<PageNumber>' . $page_number . '</PageNumber>' .
			  	'</Pagination>' .
				'<DurationInDays>60</DurationInDays>' .
			'</UnsoldList>' .
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
		$ebay_skus = array();

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

						if( ! in_array($item["SKU"], $ebay_skus) ){
							array_push( $ebay_skus, $item["SKU"]);
						}

						$this->wpdb->replace("ebay", array(
							"item_id" => $item["ItemID"],
							"sku" => $item["SKU"],
							"data" => json_encode($item),
							"status" => "active"
						));
						
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

		return ["count" => count( $items ), "ebay_items" => $items, "active_skus" => $all_skus, "undefined_items" => $undefined_items, "ebay_skus" => $ebay_skus ];

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

	public function getItemTransactions($item_id){


		$apiURL = "https://api.ebay.com/ws/api.dll";
		
		$post_data = 
		'<?xml version="1.0" encoding="utf-8"?>' .
		'<GetItemTransactionsRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
		'<RequesterCredentials>' .
		'<eBayAuthToken>' . get_option("wpt_access_token")  . '</eBayAuthToken>' .
		'</RequesterCredentials>' .
		'<ItemID>' . $item_id .'</ItemID>' .
		'</GetItemTransactionsRequest>';	


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
					'X-EBAY-API-CALL-NAME:GetItemTransactions',
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

	public function refreshTransaction(){


		$results = $this->wpdb->get_results("
			SELECT * FROM ebay where status = 'active'
		");
		
		$items = [];

		foreach($results as $result){					
			if($result->transaction == null){
				array_push($items, $result->item_id);
			}
		}

		$apiURL = "https://api.ebay.com/ws/api.dll";

		$multiCurl = array();
		$result = array();
		$mh = curl_multi_init();

		// Setup Multi Curl Requests

		for( $i = 0; $i <= count($items) -1; $i++ ){

			$post_data = 
			'<?xml version="1.0" encoding="utf-8"?>' .
			'<GetItemTransactionsRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
			'<RequesterCredentials>' .
			'<eBayAuthToken>' . get_option("wpt_access_token")  . '</eBayAuthToken>' .
			'</RequesterCredentials>' .
			'<ItemID>' . $items[ $i ] .'</ItemID>' .
			'</GetItemTransactionsRequest>';	
			
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
						'X-EBAY-API-CALL-NAME:GetItemTransactions',
						'X-EBAY-API-IAF-TOKEN::' . get_option("wpt_access_token")
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

			$curl_result  = curl_multi_getcontent($ch);

			$xml = simplexml_load_string( $curl_result ) or die("Error: Cannot create object");
			$json = json_decode(json_encode($xml), true);
			
			$result[$k] = $json;

			if( $json["Ack"] == "Success" ){

				$status = "active";
				$transaction = null;

				if($json["Item"]["SellingStatus"]["ListingStatus"] == "Completed") {

					$status = "completed";

					if( array_key_exists("TransactionArray", $json) ) {
						$transaction = json_encode( $json["TransactionArray"] );
					} else {
						$transaction = "Not Sold";
					}	

				}
				elseif( $json["Item"]["SellingStatus"]["ListingStatus"] == "Active" ) {

					if( $json["Item"]["SellingStatus"]["QuantitySold"] == "0" ){

						$status = "active";

					}
					elseif( $json["Item"]["SellingStatus"]["QuantitySold"] == "1" ){

						$status = "awaiting";
						
					}

					$transaction = null;
				}


				$this->wpdb->update(
					"ebay", 
					array(
						"transaction" => $transaction,
						"status" => $status
					),
					array(
						"item_id" => $json["Item"]["ItemID"]
					)
				);

			}

			curl_multi_remove_handle($mh, $ch);

		}

		// Close Multi Curl
		curl_multi_close($mh);		

		return $result;


	}

	// NEW ROUTINES

	public function getEbayItems($type = null, $page = null, $days = null){

		if( $days == null ){
			$days_count = 60;
		} 
		else {
			$days_count = $days;
		}


		if( $type == null || $type == "active"){
			$switch = "ActiveList";
			$duration = '';
			$sort = "<Sort>TimeLeft</Sort>";
		}
		elseif( $type == "sold" ){
			$switch = "SoldList";
			$duration = '<DurationInDays>' . $days_count . '</DurationInDays>';
			$sort = "";
		}
		elseif( $type == "unsold" ){
			$switch = "UnsoldList";
			$duration = '<DurationInDays>' . $days_count . '</DurationInDays>';
			$sort = "";
		}

		if( $page == null ){
			$page_number = 1;
		}
		else {
			$page_number = $page;
		}


		$per_page = 200;

		$apiURL = "https://api.ebay.com/ws/api.dll";

		$post_data = 
		'<?xml version="1.0" encoding="utf-8"?>' .
		'<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
		'<RequesterCredentials>' .
			'<eBayAuthToken>' . $this->access_token  . '</eBayAuthToken>' .
			'</RequesterCredentials>' .
			'<ErrorLanguage>en_US</ErrorLanguage>' .
			'<WarningLevel>High</WarningLevel>' .
			'<' . $switch . '>' .
				$sort .
				'<Pagination>' .
					'<EntriesPerPage>' . $per_page . '</EntriesPerPage>' .
					'<PageNumber>' . $page_number . '</PageNumber>' .
				'</Pagination>' .
				$duration .
			'</' . $switch . '>' .
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
					
					return array("error" => true, "response"=> "Refresh Access Token");

				} else {

					return array("error" => true, "response"=> $json);

				}
	
			} else {

				$items = [];
				$requestType = "";
				$TotalNumberOfPages = 0;
				$TotalNumberOfEntries = 0;

				// SOLD LIST
				if( array_key_exists( "SoldList", $json ) ){

					$requestType = "SoldList";

					if( array_key_exists( "OrderTransactionArray", $json[ $requestType ]) ){

						if( array_key_exists("OrderTransaction", $json[ $requestType ]["OrderTransactionArray"])){

							foreach( $json[ $requestType ]["OrderTransactionArray"]["OrderTransaction"] as $order_transaction ){

								if( array_key_exists( "Order", $order_transaction) ){

									foreach( $order_transaction["Order"]["TransactionArray"]["Transaction"] as $transaction ){

										array_push( $items, $transaction );

									}
								
								}
								elseif( array_key_exists( "Transaction", $order_transaction) ){

									array_push( $items, $order_transaction["Transaction"] );

								}

							}

						}

					} 
					else {

					}

					if( array_key_exists("PaginationResult", $json[ $requestType ]) ){

						$TotalNumberOfPages = $json[ $requestType ]["PaginationResult"]["TotalNumberOfPages"];
						$TotalNumberOfEntries = $json[ $requestType ]["PaginationResult"]["TotalNumberOfEntries"];

					}

				}
				
				return array(
						"error" => false, 
						"response"=> $json, 
						$requestType => $items, 
						"current_page" => $page_number,
						"pages" =>  $TotalNumberOfPages, 
						"entries" => $TotalNumberOfEntries 
					);

			}
	
		} else {
			return "Not Valid JSON";
		}

	}				


}