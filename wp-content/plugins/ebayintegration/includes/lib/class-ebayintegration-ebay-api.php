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

		register_rest_route( 
			'/ebayintegration/v1', 
			'ajax', 
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'handle_api_endpoint' )
			) 
		);        

		register_rest_route( 
			'/ebayintegration/v1', 
			'post', 
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'handle_api_post_endpoint' )
			) 
		);        

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

		// elseif($params["action"] == "getItems"){

		// 	if( isset( $params["page_number"] ) ){
		// 		$page_number = $params["page_number"];
		// 	} else {
		// 		$page_number = 1;
		// 	}

		// 	return $this->getItems($page_number);
		// } 

		// elseif($params["action"] == "getItemPages"){
			
		// 	$pages = $this->GetItemPages();

		// 	if($pages["error"] == false ){

		// 		return $this->getItemsMulti( $pages["data"], 200);

		// 	} else {
		// 		return "Error getting item pages";
		// 	}
			
		// } 

		// elseif($params["action"] == "getItemInfo"){

		// 	return $this->getItemInfo($params["item_id"]);

		// } 

		// elseif($params["action"] == "getItemTransactions"){

		// 	return $this->getItemTransactions($params["item_id"]);

		// } 		

		// elseif($params["action"] == "refreshTransactions"){

		// 	return $this->refreshTransaction();

		// } 		

		// ///////////////////////
		//
		// EBAY ACTIONS
		//
		// ///////////////////////


		elseif($params["action"] == "refreshToken"){

			return $this->refreshToken();

		} 

		elseif($params["action"] == "getEbayItems"){

			return $this->getEbayItems($params["type"], $params["page"], $params["days"]);

		} 		

		// ///////////////////////
		//
		// USER SKU ACTIONS
		//
		// ///////////////////////


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


	public function handle_api_post_endpoint( $data ){

		$params = $data->get_params();

		if( $params["action"] == "confirmAddConsign"){
			return $this->confirmAddConsign( $params );
		}
		
		elseif( $params["action"] == "confirmConsignCardsShipping"){
			return $this->confirmConsignCardsShipping( $params );
		}

		elseif( $params["action"] == "removeConsignedCardRow"){
			return $this->removeConsignedCardRow( $params );
		}

		elseif( $params["action"] == "confirmConsignedCardReceived"){
			return $this->confirmConsignedCardReceived( $params );
		}

		elseif( $params["action"] == "consignedCardNotReceived"){
			return $this->consignedCardNotReceived( $params );
		}
		
		elseif( $params["action"] == "confirmUnvailableConsignedCard"){
			return $this->confirmUnvailableConsignedCard( $params );
		} 

		elseif( $params["action"] == "showConsignedCardDetailsModal"){
			return $this->showConsignedCardDetailsModal( $params );
		} 

		elseif( $params["action"] == "confirmUpdateConsignedCardDetails"){
			return $this->confirmUpdateConsignedCardDetails( $params );
		} 
				
		elseif( $params["action"] == "getViewMemberEbay"){
			return $this->getViewMemberEbay( $params );
		} 

		elseif( $params["action"] == "getViewMemberDetails"){
			return $this->getViewMemberDetails( $params );
		} 

		elseif( $params["action"] == "getViewMemberSKU"){
			return $this->getViewMemberSKU( $params );
		} 

		else {
			return $params;
		}		

	}

	// MEMBERS

	public function getViewMemberSKU( $params ){

		$skus = get_user_meta( $params["user_id"], "sku", true );

		return [ "sku" => $skus ];

	}
	

	public function getViewMemberDetails( $params ){

		$sql = "SELECT * FROM wp_users WHERE ID = '" . $params["user_id"] . "'";
		$user = $this->wpdb->get_results ( $sql );

		return [ "user" => $user ];

	}

	public function getViewMemberEbay( $params ){

		$skus = get_user_meta( $params["user_id"], "sku", true );		

		$array = implode("','",$skus);

		$sql = "SELECT * FROM ebay WHERE sku IN ('" . $array . "')";

		$cards = $this->wpdb->get_results ( $sql );

		return ["card" => $cards, "skus" => $skus, "sql" => $sql ];
	}

	// CONSIGNMENT



	public function confirmUpdateConsignedCardDetails( $params ){

		$rows = $this->wpdb->update(
			'consignment',
			array(
				'status' => $params["new_status"],
			), 
			array(
				"id" => $params["id"],
			)
		);

		if( $rows != false ){
			return ["error" => false, "params" => $params ];
		} else {
			return ["error" => true, "params" => $params ];
		}

	}

	public function showConsignedCardDetailsModal( $params ){

		$card = $this->wpdb->get_results ( "
			SELECT 
				consignment.*,
				wp_users.user_email,
				wp_users.display_name
			FROM consignment
				INNER JOIN wp_users
				ON consignment.user_id = wp_users.ID
			where consignment.id = " . $params["id"] . "
		");

		if( $card != null ){
			return ["error" => false, "params" => $params, "card" => $card[0] ];
		} else {
			return ["error" => true, "params" => $params ];
		}

		return $params;
	}

	public function confirmUnvailableConsignedCard( $params ){

		$rows = $this->wpdb->update(
			'consignment',
			array(
				'status' => "confirmed-not-available",
			), 
			array(
				'user_id' => $params["user_id"],
				"id" => $params["id"],
			)
		);

		if( $rows != false ){
			return ["error" => false, "params" => $params ];
		} else {
			return ["error" => true, "params" => $params ];
		}

	}

	public function consignedCardNotReceived( $params ){

		$rows = $this->wpdb->update(
			'consignment',
			array(
				'status' => "unavailable",
			), 
			array(
				"id" => $params["id"],
			)
		);

		if( $rows != false ){
			return ["error" => false, "params" => $params ];
		} else {
			return ["error" => true, "params" => $params ];
		}

	}

	public function confirmConsignedCardReceived( $params ){

		$rows = $this->wpdb->update(
			'consignment',
			array(
				'status' => "received",
			), 
			array(
				"id" => $params["id"],
			)
		);

		if( $rows != false ){
			return ["error" => false, "params" => $params ];
		} else {
			return ["error" => true, "params" => $params ];
		}

	}

	public function removeConsignedCardRow( $params ){

		$rows = $this->wpdb->delete(
			'consignment',
			array(
				'user_id' => $params["user_id"],
				"id" => $params["id"],
			)
		);

		if( $rows != false ){
			return ["error" => false, "params" => $params ];
		} else {
			return ["error" => true, "params" => $params ];
		}

	}

	public function confirmConsignCardsShipping( $params ){

		$user_id = (int) $params["user_id"]; 

		$data = [
			"carrier" => $params["carrier"],
			"shipped_by" => $params["shipped_by"],
			"tracking_number" => $params["tracking_number"],
			"shipping_date" => $params["shipping_date"],
		];

		$this->wpdb->insert(
			'consignment_orders',
			array(
				'user_id' => $user_id,
				"data" => json_encode($data),
			)
		);

		$lastid = $this->wpdb->insert_id;					
			
		$data["order_id"] = $lastid;
		$data["user_id"] = $user_id;

		$this->wpdb->update(
			'consignment', 
			array(
				'order_id' => $data["order_id"],
				'status' => "shipped",
			), 
			array(
				'user_id' => $user_id,
				'status' => "logged",
			)
		);		


		return $data;

	}

	public function confirmAddConsign( $params ){

		$user_id = (int) $params["user_id"]; 
		$result = [];

		for( $i=0; $i < $params["qty"]; $i++ ){

			$data = [
				"qty" => 1,
				"year" => $params["year"],
				"brand" => $params["brand"],
				"card_number" => $params["card_number"],
				"player_name" => $params["player_name"],
				"attribute_sn" => $params["attribute_sn"],
			];


			$this->wpdb->insert(
						'consignment',
						array(
							'user_id' => $user_id,
							"data" => json_encode($data),
						)
					);

			$lastid = $this->wpdb->insert_id;					
			
			$data["id"] = $lastid;
			$data["user_id"] = $user_id;

			$result[] = $data;


		}


		return $result;


	}	


	// EBAY ROUTINES

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
			$switch_filter = "";
			$sort = "<Sort>TimeLeft</Sort>";
		}
		elseif( $type == "sold" ){
			$switch = "SoldList";
			$duration = '<DurationInDays>' . $days_count . '</DurationInDays>';
			$switch_filter = "<OrderStatusFilter>PaidAndShipped</OrderStatusFilter>";
			$sort = "";
		}
		elseif( $type == "unsold" ){
			$switch = "UnsoldList";
			$duration = '<DurationInDays>' . $days_count . '</DurationInDays>';
			$switch_filter = "";
			$sort = "";
		}
		elseif( $type == "awaiting" ){
			$switch = "SoldList";
			$duration = '<DurationInDays>' . $days_count . '</DurationInDays>';
			$switch_filter = "<OrderStatusFilter>AwaitingPayment</OrderStatusFilter>";
			$sort = "";
		}
		elseif( $type == "unsold" ){
			$switch = "UnsoldList";
			$duration = '<DurationInDays>' . $days_count . '</DurationInDays>';
			$switch_filter = "";
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
				$switch_filter .
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

				}
				elseif( array_key_exists( "ActiveList", $json ) ){

					$requestType = "ActiveList";


					if( array_key_exists( "ItemArray", $json[ $requestType ]) ){
						if( array_key_exists( "Item", $json[ $requestType ]["ItemArray"]) ){
							foreach( $json[ $requestType ]["ItemArray"]["Item"] as $item ){
								array_push( $items, $item );
							}
						}
					}


				}
				elseif( array_key_exists( "UnsoldList", $json ) ){

					$requestType = "UnsoldList";

					if( array_key_exists( "ItemArray", $json[ $requestType ]) ){
						if( array_key_exists( "Item", $json[ $requestType ]["ItemArray"]) ){
							foreach( $json[ $requestType ]["ItemArray"]["Item"] as $item ){
								array_push( $items, $item );
							}
						}
					}


				}

				if( array_key_exists("PaginationResult", $json[ $requestType ]) ){
					$TotalNumberOfPages = $json[ $requestType ]["PaginationResult"]["TotalNumberOfPages"] * 1;
					$TotalNumberOfEntries = $json[ $requestType ]["PaginationResult"]["TotalNumberOfEntries"] * 1;
				}

				

				$returnArray = array(
						"error" => false, 
						"response"=> $json, 
						$requestType => $items, 
						"type" => $type,
						"current_page" => $page_number * 1,
						"pages" =>  $TotalNumberOfPages, 
						"entries" => $TotalNumberOfEntries 
					);

				foreach( $items as $item ){

					if( $type == "active" ){
						$itemID = $item["ItemID"];
						$SKU = $item["SKU"];
						$itemstatus = "ActiveList";
					}
					elseif( $type == "unsold" ){
						$itemID = $item["ItemID"];
						$SKU = $item["SKU"];
						$itemstatus = "UnsoldList";
					}
					elseif( $type == "awaiting" ){
						$itemID = $item["Item"]["ItemID"];
						$SKU = $item["Item"]["SKU"];
						$itemstatus = "SoldListAwaiting";							
					}
					elseif( $type == "sold" ){
						$itemID = $item["Item"]["ItemID"];
						$SKU = $item["Item"]["SKU"];
						$itemstatus = "SoldListPaid";							
					}


					$result = $this->wpdb->get_results ("
						SELECT * 
						FROM  ebay
						WHERE item_id = " . $itemID 
					);				


					if( count($result) > 0 ){
						
						if( $type == "sold" ){

							$result = $this->wpdb->get_results ("
								SELECT * 
								FROM  ebay
								WHERE item_id = " . $itemID . "
								AND status = 'PaidOut'
								"
							);				

							if ( count($result) == 0 ){

								$this->wpdb->update(
									'ebay', 
									array(
										'data'=> json_encode($item), 
										'status'=>$itemstatus,
									), 
									array(
										'item_id'=>$itemID)
								);		

							}

						} 
						else {
							
							$this->wpdb->update(
								'ebay', 
								array(
									'data'=> json_encode($item), 
									'status'=>$itemstatus,
								), 
								array(
									'item_id'=>$itemID)
							);
	
						}

					} 
					else {

						$this->wpdb->insert(
							'ebay',
							array(
								'item_id' => $itemID,
								"sku" => $SKU,
								"data" => json_encode($item),
								"status" => $itemstatus,			
							)
						);
					}
				}


				return $returnArray;

			}
	
		} else {
			return "Not Valid JSON";
		}

	}				


}