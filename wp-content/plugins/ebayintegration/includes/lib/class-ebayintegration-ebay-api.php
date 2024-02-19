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

		$this->access_token = "v^1.1#i^1#p^3#r^0#f^0#I^3#t^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";
		$this->refresh_token = "v^1.1#i^1#r^1#p^3#I^3#f^0#t^Ul4xMF83OkZFMDQ5NTk4NkI0MzgyRjVFQTY2QzQ3NEIxNDY0RkMxXzBfMSNFXjI2MA==";
		$this->authorization = "Basic RmVybmFuZG8tNXN0YXJjYXItUFJELWE4MWZkZDE4OS1hNzYyZGZjNzpQUkQtODFmZGQxODljYmYxLWY0NzItNDEzMS05M2EyLTE5OTA=";
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
			return array("error"=> false, "data" => $this->handleGetItems());
		} 
		else {
			return array("error"=> true, "error_message" => $params["action"] . " - Action Not Defined");
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
			<button class="ebayintegration-btn" data-action="getItems">Get eBay Items</button>
		</div>		
		<div class="ebayintegration-items_box">
		</div>		
		<?php
        
        $output = ob_get_clean(); 
        
        return $output ;
    }
     
}