<?php
/**
 * .
 *
 * @package Ebay Integration/Ebay API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings class.
 */
class Ebay_Integration_Ebay_API {

	/**
	 * The single instance of Ebay_Integration_Ebay_API.
	 *
	 * @var     object
	 * @access  private
	 * @since   1.0.0
	 */
	private static $_instance = null; //phpcs:ignore

	/**
	 * The main plugin object.
	 *
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $parent = null;

    public $access_token;		
	public $refresh_token;

	
	public function __construct( $parent ) {

		$this->parent = $parent;

        add_action("rest_api_init", array($this, 'create_ebay_enpoint'));

	}

	 public function create_ebay_enpoint( ){

		register_rest_route( '/ebayintegration/v1', '/request', array(
			'methods' => 'GET',
			'callback' => array( $this, 'handle_api_endpoint' )
		) );        

    }

	function handle_api_endpoint($data){

		$executed = false;
		$max_retry = 5;
		$retries = 0;
		$result = "";
		
		while($executed == false){
		
			$retries++;
			$result = getItems();
		
			if($result["Ack"] == "Success"){
				$executed = true;
			} 
			elseif($result["Ack"] == "Failure"){
				refreshToken();
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
		

	public static function instance( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cloning of Ebay_Integration_API is forbidden.' ) ), esc_attr( $this->parent->_version ) );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Unserializing instances of Ebay_Integration_API is forbidden.' ) ), esc_attr( $this->parent->_version ) );
	} // End __wakeup()

     
}