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

		return get_option("access_token");
		
		$apiURL = "https://api.ebay.com/ws/api.dll";
		
		$post_data = 
		'<?xml version="1.0" encoding="utf-8"?>' .
		'<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
		'<RequesterCredentials>' .
		  '<eBayAuthToken>' . get_option("access_token")  . '</eBayAuthToken>' .
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