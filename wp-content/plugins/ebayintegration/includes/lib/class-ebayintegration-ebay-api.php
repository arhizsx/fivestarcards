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
    public $per_page;		

	
	public function __construct( ) {

		$this->access_token = get_option("wpt_access_token");
		$this->refresh_token = get_option("wpt_refresh_token");
		$this->authorization = get_option("wpt_authorization");
		$this->content_type = "application/x-www-form-urlencoded";
		$this->per_page = 50;

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
			
			$user_id = 463;
			$meta = "sku";
			$value = array ('Kevin Romano - 9092', 'test');  

			update_user_meta( $user_id, $meta, $value);

			return $this->GetItemPages();

		} 
		elseif($params["action"] == "getItemInfo"){

			return $this->getItemInfo($params["item_id"]);

		} 
		elseif($params["action"] == "refreshToken"){

			return $this->refreshToken();

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

				

				return array("error" => false, "data"=> $this->processItems($json["ActiveList"]["ItemArray"]["Items"]["Item"]) );
				
			}
	
		} else {
			return "Not Valid JSON";
		}
		
	}
		

	public function processItems($items){
		
		global $wpdb;     

		return $items;

		foreach($items as $item){

			$wpdb->insert("ebay", array(
				"item_id" => $item["ItemID"],
				"data" => $item,
				"status" => "active"
			));
		}

		return "Processed Items " . $items;

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