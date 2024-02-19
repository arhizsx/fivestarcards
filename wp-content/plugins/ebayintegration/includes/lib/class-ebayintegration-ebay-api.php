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

		$this->access_token = "^1.1#i^1#I^3#f^0#r^0#p^3#t^H4sIAAAAAAAAAOVZaYwbVx1f7xGyTUI5om4JVeVOG1VNNfYcvma0tvDaTtbd7K5je51kJbCe37zxvng845337LWbgFYpCgpHq4DSCtq0oUD7IYhEVFE51KpLJcqhQloJBamkCCQuISRKS/hAo/DG3t1stmr2cLRY4C/WvPe/fv/rXcLspv5dx4aP/Wub6wPdp2eF2W6XS9wi9G/qu/+DPd07+rqEJQSu07P3zPYe7fnzIAFlo6KmEalYJkHuetkwidocDHNV21QtQDBRTVBGRKVQzURH96qSR1ArtkUtaBmcOxkPc0AQgC75RL1Q8CmhkMZGzQWZWSvMIRSSoaYFdF1WJAQUNk9IFSVNQoFJw5wkSD5ekHhRyQpB1edTZdnjCyqTnDuHbIItk5F4BC7SNFdt8tpLbL2xqYAQZFMmhIsko7sz49FkPDGWHfQukRWZ90OGAlol13/FLA25c8CoohurIU1qNVOFEBHCeSMtDdcLVaMLxqzD/KarNU0qaApSgkrQL0iCeFNcuduyy4De2A5nBGu83iRVkUkxbazkUeaNwiEE6fzXGBORjLudv31VYGAdIzvMJYaiBycyiTTnzqRStlXDGtIcpKIkCgFRloUQF/HnGTw7D4GtkXk9LWHzXl6mKGaZGnZ8RtxjFh1CzGi03DXyEtcwonFz3I7q1DFoKZ2y6ELfpBPTVhCrdMp0worKzA/u5ufKAVjIiGs5cLNyQgwBPxKVkBAoMN/BwHtzwqn1tedFxAlNNJXyOragAmjwZWCXEK0YACIeMvdWy8jGmir7dUkO6YjXAorO+xRd5wt+LcCLOkICQoUCVEL/R+lBqY0LVYoWU2T5RBNjmMtAq4JSloFhg1tO0uw48wlRJ2FuitKK6vXOzMx4ZmSPZRe9kiCI3gOjezNwCpUBt0iLVybmcTM1IGJcBKu0UWHW1FnmMeVmkYvItpYCNm1kkGGwgYW8vc62yPLR9wEZMzDzQJap6CyMwxahSGsLmoZqGKI81joKmVPrEX/IJ4hi0B8UBF9bGA2riM1RRKeszkIZcXpCMt4WNtZCAe0sVGJQCImSFPDLbSGjM5YOOjFq2fREJpuI5+OJXDKWaAtjtFJJlstVCgoGSnYYTF9I8fuFtuBVqtWVG4tT6xuLrDRkD1miOL1P5NuC52wnVAx0lVolZHbe8pBO7E4nMsP57PhIYqwtpGmk24hMZR2cnZan0X3RB6LsNxqne2K4lq5DcSJr5GxfwzvdSJYnS1jL2QcUlH0whkaGGqV0bTJpj1FfzRrBSTE4SsegPMz0V/aFw205KYOgjTqsF6Oh4gwdzqX2eu+HM+aIX6qj/fJkLECn4oGpOi7limQyXpqQyKFEe+BHi6vZQji1vpHwb9oWItuZFW636jLfbEB59tUWyERxFe16YwEGdSWoQ00TQ5IANHZS8kuyIkN2OtMLBT3QHl5n9e0wvLuRbQJTs3i/cxpkh0E+lY7zICTqjg8UHgQDkqbDYJvL8kaE2an1/8aqTJwTaWdF1eEnTACoYI+zafBAq+y1QJVOOUP5psXu1RB5CTvNelo3GEyyx0ZAs0yjsR7mNfBgs8bOv5bdWI/CReY18AAIrapJ16NunnUNHHrV0LFhOJcc61G4hH0tZprAaFAMybpUYtPJNrIGlgpoNAFqmFScWlkVJxsrIxsiD9Zal6XvZ6xT6zfSbiOmFDTvB1dv8jWmNfpo0WzToljHsCWDVAsE2riyeitWlrOe4BFWC2sKXYthUVV71wVIwzaCNF+1cWf1yIWVL58BRg1o/LKVkJ+us/xtD7zj3E68UEhFM5n94+n2LoLiqLYxm5neo67ja9iBS0Bi+xWJD8JggfcFBY0PQb3A66GAUgjJoYAUhG3h/h+4/lo2sOTK/T2PLd7rHzsjXc2feNT1I+Go68Vul0sYFHaKdwt3beqZ6O3ZuoNgyro30D0EF01AqzbylFCjArDd/dGufzx9cji2IzH+6K7D2caFx1/p2rrkrfX0J4XbF19b+3vELUueXoU7rs30ibcObJN8giQqQtDnk+VJ4e5rs73ibb3bB4pXnzr/6vTbv73gOfy1+Ltzfxw7+KawbZHI5errYknVVdi8U597ZPwPnzv+xtmff+P3l37y9T3B1098s/Rh/ciZC+rcpwYf/Of+wa2xj3z27EX3b66+/eRn7vyh597bPp8de+b4yeItAw8cGY2ce6s79vTOX8dPR87609/JbQ8/dOYHX73ywu0TZ6bvfefU4FdevfIS+Oljd76y91uca/ieW57a/nK/DX/n/fjo+blTc/3fg6/ltXdzn8g+psYgzvzyb69LPbc+c/HQlZenf3G8PvDlkauJRw+88U74/Inn93zhxMP1Xce21PS7th8MDbzVEB86+e1zl7906fDDm899N/Pmxcs27n4W/+yv5378xIvpO0a+WH8y/cJLWy5f+lAf+Xv55OZf8R/bw536y32Z5y69Jn7/00cu/vtPI61Y/gfzjrvfBR8AAA==";
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
			return array("error"=> false, "data" => $this->getItems($params["page_number"]));
		} 
		elseif($params["action"] == "getItemPages"){
			return $this->handleGetItemPages();
		} 
		else {
			return array("error"=> true, "error_message" => $params["action"] . " - Action Not Defined");
		}
	
	}

	public function refreshToken(){
		return true;
	}
	public function handleGetItemPages(){

		$executed = false;
		$max_retry = 5;
		$retries = 0;
		$result = "";
		$per_page = 2;
		$page_number = 1;

		while($executed == false){
		
			$retries++;

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

		
			if($json["Ack"] == "Success"){
				$executed = true;
			} 
			elseif($json["Ack"] == "Failure"){
				$result = $this->refreshToken();
			}
		
			if($retries == $max_retry){
				$executed = true;
			}
		}




		if( count($json["ActiveList"]["ItemArray"]["Item"]) == 2){

			$entries = $result["ActiveList"]["PaginationResult"]["TotalNumberOfEntries"];
			$pages = ceil($entries / 50);

			return $pages;
			
		} else {
			return $result;
		}


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

	public function getItems($page_number = 1,  $per_page = 50){
		
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
			<button class="ebayintegration-btn" data-action="getItems">Get Active eBay Items</button>
		</div>		
		<div class="ebayintegration-items_box">
		</div>		
		<?php
        
        $output = ob_get_clean(); 
        
        return $output ;
    }
     
}