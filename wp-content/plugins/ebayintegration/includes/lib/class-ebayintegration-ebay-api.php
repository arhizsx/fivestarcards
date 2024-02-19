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

		$this->access_token = get_option("wpt_access_token");
		$this->refresh_token = get_option("wpt_refresh_token");
		$this->authorization = get_option("wpt_authorization");
		$this->content_type = "application/x-www-form-urlencoded";

        add_action("rest_api_init", array($this, 'create_ebay_enpoint'));

		add_shortcode('ebayintegration-getItem', array( $this, 'getItem_shortcode' ));

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
			return $this->getItems($params["page_number"]);
		} 
		elseif($params["action"] == "getItemPages"){
			return $this->GetItemPages();
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

		update_option("wpt_access_token", $response["access_token"]);
		return $response;

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
					$pages = ceil($entries / 50);
	
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

	public function getItems($page_number = 1,  $per_page = 50){
		
		$apiURL = "https://api.ebay.com/ws/api.dll";
		
		$post_data = 
		'<?xml version="1.0" encoding="utf-8"?>' .
		'<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
		'<RequesterCredentials>' .
		  '<eBayAuthToken>v^1.1#i^1#r^0#p^3#I^3#f^0#t^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</eBayAuthToken>' .
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

				return array("error" => false, "data"=> $json);
				
			}
	
		} else {
			return "Not Valid JSON";
		}
		
	}
		
    public function getItem_shortcode($atts) 
    {

        $default = array(
            'title' => 'getItem',
            'type' => 'getItem'
        );
        
        $params = shortcode_atts($default, $atts);
        ob_start();
		?>
		<div>
			<button class="ebayintegration-btn" data-action="getItems">Get Active eBay Items</button>
		</div>		
		<div class="ebayintegration-items_box">
		</div>		
		<?php
        
        $output = ob_get_clean(); 
        
        return $output ;
    }
     
}