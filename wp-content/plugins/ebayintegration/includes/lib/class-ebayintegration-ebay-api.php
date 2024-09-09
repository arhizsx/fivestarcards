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


		// ///////////////////////
		//
		// EBAY ACTIONS
		//
		// ///////////////////////

		elseif($params["action"] == "getItemInfo"){

			return $this->getItemInfo($params["item_id"]);

		} 

		elseif($params["action"] == "autoRefreshComplete"){

			return $this->autoRefreshComplete();

		} 


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
		$files =  $data->get_file_params();


		if( $params["action"] == "confirmAddConsign"){
			return $this->confirmAddConsign( $params );
		}

		elseif( $params["action"] == "confirmPayoutRequest"){
			return $this->confirmPayoutRequest( $params );
		}

		elseif( $params["action"] == "getPayoutRequest"){
			return $this->getPayoutRequest( $params );
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

		elseif( $params["action"] == "getViewUnmatchedSKU"){
			return $this->getViewUnmatchedSKU( $params );
		} 

		elseif( $params["action"] == "removeMemberSKU"){
			return $this->removeMemberSKU( $params );
		} 

		elseif( $params["action"] == "addUnmatchedSKU"){
			return $this->addUnmatchedSKU( $params );
		} 

		elseif( $params["action"] == "deactivateMember"){
			return $this->deactivateMember( $params );
		} 

		elseif( $params["action"] == "saveMemberDetailsChanges"){
			return $this->saveMemberDetailsChanges( $params );
		} 
		
		elseif( $params["action"] == "consignmentPaidOut"){
			return $this->consignmentPaidOut( $params );
		} 

		elseif( $params["action"] == "consignmentPaidOutQueue"){
			return $this->consignmentPaidOutQueue( $params );
		} 

		elseif( $params["action"] == "consignmentPaidOutRelease"){
			return $this->consignmentPaidOutRelease( $params );
		} 
		
		elseif( $params["action"] == "confirmAddGrading"){
			return $this->confirmAddGrading( $params );
		}

		elseif( $params["action"] == "removeGradingCardRow"){
			return $this->removeGradingCardRow( $params );
		}		

		elseif( $params["action"] == "confirmGradingTableClearList"){
			return $this->confirmGradingTableClearList( $params );
		}		
		
		elseif( $params["action"] == "confirmGradingTableCheckout"){
			return $this->confirmGradingTableCheckout( $params );
		}		

		elseif( $params["action"] == "confirmUploadGradingFile"){

			return  $this->confirmUploadGradingFile($params, $files);
			
		}

		elseif( $params["action"] == "loginToAccount"){

			return  $this->loginToAccount($params);
			
		}

		elseif( $params["action"] == "messageUser"){

			return $this->messageUser($params, $files);

		}

		elseif( $params["action"] == "registerUser"){

			return $this->registerUser($params);

		}
		
		elseif( $params["action"] == "confirmPayoutDone"){

			return $this->confirmPayoutDone($params);

		}

		elseif( $params["action"] == "grading_checkbox"){

			return $this->grading_checkbox($params);

		}

		elseif( $params["action"] == "remove_grading_file"){

			return $this->remove_grading_file($params);

		}

		elseif( $params["action"] == "card_grade_saving"){

			return $this->card_grade_saving($params);

		}
		
		
		else {			

			return $params;
		}		

	}

	// MEMBERS

	function registerUser($params){
		$user_id = wp_create_user( $params["display_name"], "5StarCardsPassword", $params["user_email"] );

		if( is_numeric($user_id ) ){
			return ["error" => false, "user_id" => $user_id];
		} else {
			return ["error" => true, "message" => "Failed Creating New User"];
		}

	}

	function messageUser( $params, $files ) {

		$headers[] = 'Cc: arhizsx@gmail.com';
		$attachments = [];


		$uploads = [];
		$upload_folder = wp_get_upload_dir();
		$upload_folder = $upload_folder["basedir"];


		foreach($files as $k => $v){


				$fileName = 'attachment-' . rand( time() , 1000 ) . "" .  $v["name"];
				$file = file_get_contents( $v["tmp_name"] );
				$filesize = file_put_contents( $upload_folder."/cards/".$fileName, $file );

				$attachments[]  = $upload_folder . "/cards/" . $fileName;		

		}


		
		return wp_mail($params["user_email"], $params["subject"], $params["message"], $headers, $attachments);		
		// return $params;
	}


	public function loginToAccount( $params ){

		wp_set_auth_cookie( $params["data"]["user_id"], true );

		return true;
		
	}

	public function consignmentPaidOutRelease( $params ){
		
		return [ "error" => false, "id" => $params["id"] ];

	}

	public function consignmentPaidOutQueue( $params ){
		
		$rows = $this->wpdb->update(
			'ebay',
			array(
				'status' => "PaidOutQueued"
			), 
			array(
				"id" => $params["id"],
			)
		);

		if( $rows != false ){
			return [ "error" => false, "id" => $params["id"] ];
		} 
		else {
			return [ "error" => false, "id" => $params["id"] ];

		}

	}
	
	public function consignmentPaidOut( $params ){

		$rows = $this->wpdb->update(
			'ebay',
			array(
				'status' => "PaidOut",
				'paid_out_date' => date('Y-m-d', time())
			), 
			array(
				"id" => $params["id"],
			)
		);

		if( $rows != false ){
			return [ "error" => false, "id" => $params["id"] ];
		} 
		else {
			return [ "error" => false, "id" => $params["id"] ];
		}

	}

	public function saveMemberDetailsChanges( $params ){

		$rows = $this->wpdb->update(
			'wp_users',
			array(
				'display_name' => $params["display_name"],
				'user_email' => $params["user_email"],
			), 
			array(
				"ID" => $params["user_id"],
			)
		);

		return [ "error" => false, "user_id" => $params["user_id"] ];

	}

	public function deactivateMember( $params ){


		$rows = $this->wpdb->update(
			'wp_users',
			array(
				'active' => 1,
			), 
			array(
				"ID" => $params["user_id"],
			)
		);

		return [ "error" => false, "user_id" => $params["user_id"] ];

	}

	public function addUnmatchedSKU( $params ){


		$old_metas = get_user_meta( $params["user_id"], 'sku', true );

		if( $old_metas == "" ){

			add_user_meta( $params["user_id"], 'sku', array( $params["sku"] ) );

		} else {

			if( ! in_array( $params["sku"], $old_metas ) ){
				array_push( $old_metas, $params["sku"] );
			} 

			delete_user_meta( $params["user_id"], 'sku' );

			add_user_meta( $params["user_id"], 'sku', $old_metas );
		
		}

		
		return ["error" => false, "sku" => $params["sku"], "new_sku" =>  get_user_meta( $params["user_id"], 'sku', true ) ];		

	}

	public function removeMemberSKU( $params ){

		$old_metas = get_user_meta( $params["user_id"], 'sku', true );

		$new_metas = [];

		foreach( $old_metas as $meta ){

			if(  $meta != $params["sku"] ){
				array_push( $new_metas, $meta );
			}
		}

		delete_user_meta( $params["user_id"], 'sku' );

		add_user_meta( $params["user_id"], 'sku', $new_metas );
		
		return ["error" => false, "sku" => $params["sku"], "new_sku" =>  get_user_meta( $params["user_id"], 'sku', true ) ];
		

	}

	public function getViewMemberSKU( $params ){

		$skus = get_user_meta( $params["user_id"], "sku", true );

		return [ "user_id" => $params["user_id"], "sku" => $skus ];

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

	public function getViewUnmatchedSKU( $params ){

		$users_with_sku = get_users(array(
			'meta_key' => 'sku',
		));

		$all_skus = [];

		foreach( $users_with_sku as $u ){
			$skus = get_user_meta( $u->ID, "sku", true );		
			array_push( $all_skus, ...$skus );
		}

		$ebay_skus  = $this->wpdb->get_results ("
			SELECT DISTINCT sku FROM ebay
		");

		$unmatched_skus = [];

		foreach( $ebay_skus as $esku ){
			if (!in_array( $esku->sku , $all_skus )) {
				array_push($unmatched_skus, $esku->sku );
			}
		}

		sort($unmatched_skus);

		return [ "user_id" => $params["user_id"] ,"unmatched_skus" => $unmatched_skus ];
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
				"player" => $params["player"],
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


	// GRADING

	public function confirmAddGrading( $params ){

		$user_id = (int) $params["user_id"]; 
		$type = $params["type"];
		$result = [];

		for( $i=0; $i < $params["quantity"]; $i++ ){

			$data = [
				"quantity" => 1,
				"year" => $params["year"],
				"brand" => $params["brand"],
				"card_number" => $params["card_number"],
				"player" => $params["player"],
				"attribute_sn" => $params["attribute_sn"],
				"max_dv" => $params["max_dv"],
				"dv" => $params["dv"],
				"per_card" => $params["per_card"],
				"grading_type" => $params["grading_type"],
			];

			$this->wpdb->insert(
						'grading',
						array(							
							'user_id' => $user_id,
							'type' => $params["grading_type"],
							"data" => json_encode($data),
						)
					);

			$lastid = $this->wpdb->insert_id;					
			
			$data["id"] = $lastid;
			$data["user_id"] = $user_id;

			$params["db_id"] = $lastid;
			$data["db_id"] = $lastid;

			// OLD CODE

			$user = get_user_by( "id", $user_id ); 	
	
			$post_id = wp_insert_post([
				'post_type' => 'cards-grading-card',
				'post_title' => $user->display_name . " - " . $params["player"],
				'post_status' => 'publish'
			]);

	
			add_post_meta($post_id, "checkout_id", $params["checkout_id"] );
			add_post_meta($post_id, "user_id", $params["user_id"] );
			add_post_meta($post_id, "grading", $params["grading_type"] );
			add_post_meta($post_id, "status", "pending" );
			add_post_meta($post_id, "card", json_encode($data) );
	

			// 


			$result[] = $data;


		}


		return $result;

	}		

	public function removeGradingCardRow( $params ){

		$rows = $this->wpdb->delete(
			'grading',
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

	public function confirmGradingTableClearList( $params ){

		$rows = $this->wpdb->delete(
			'grading',
			array(
				'user_id' => $params["user_id"],
				"type" => $params["type"],
			)
		);

		if( $rows != false ){
			return ["error" => false, "params" => $params ];
		} else {
			return ["error" => true, "params" => $params ];
		}


	}

	public function confirmGradingTableCheckout( $params ){

		$data = [
			"user_id" => $params["user_id"],
			"type" => $params["type"]
		];


		$sql = "SELECT * FROM grading where type='". $params["type"] .  "_file' AND user_id = '". $params["user_id"] .  "' AND status = 'logged'";
		$grading_files = $this->wpdb->get_results ( $sql );	

		if( count($grading_files) > 0){
			$status = "For Entry";
		} else {
			$status = "To Ship";
		}

		$sql = "SELECT * FROM grading_addons where type='". $params["type"] .  "' AND user_id = '". $params["user_id"] .  "'";
		$query = $this->wpdb->get_results ( $sql );	
		if( count($query) > 0 ){
			$inspection = true;
		} else {
			$inspection = null;
		}

		$this->wpdb->insert(
			'grading_orders',
			array(
				'user_id' => $params["user_id"],
				"type" => $params["type"],
				"data" => json_encode($data),		
				"status" => $status,		
				"inspection" => $inspection
			)
		);

		$lastid = $this->wpdb->insert_id;					

		$rows = $this->wpdb->update(
			'grading', 
			array(
				'status'=>"checkout",
				"order_id" => $lastid,
				"inspection" => $inspection
			), 
			array(
				'user_id' => $params["user_id"],
				"status" => "logged",
				"type" => $params["type"],
			)
		);		

		$rows = $this->wpdb->update(
			'grading', 
			array(
				'status'=>"checkout",
				"order_id" => $lastid,
				"inspection" => $inspection
			), 
			array(
				'user_id' => $params["user_id"],
				"status" => "logged",
				"type" => $params["type"] . "_file",
			)
		);		
		
		// OLD ROUTINE CODE

		$user = get_user_by( "id", $params["user_id"] );

		$args = array( 
			'meta_query' => array(
				array(
					'key' => 'type',
					'value' => $params["type"]
				)
			),
			'post_type' => 'cards-grading-type',
			'posts_per_page' => -1
		);
		
		$grading_type = get_posts($args);
		$grading_name =  get_post_meta( $grading_type[0]->ID , 'name' , true );

		$checkout_post_id = wp_insert_post([
				'post_type' => 'cards-grading-chk',
				'post_title' => $user->display_name . " - " . $grading_name,
				'post_status' => 'publish'
		]); 

		if( $inspection != null ){
			add_post_meta($checkout_post_id, "inspection",  $inspection );
		}

		add_post_meta($checkout_post_id, "user_id",  $params["user_id"] );
		add_post_meta($checkout_post_id, "service_type", "Card Grading" );
		add_post_meta($checkout_post_id, "grading_type", $grading_name );
		add_post_meta($checkout_post_id, "order_number", $checkout_post_id );


		$args = array(
			'meta_query' => array(
				'relations' =>  'AND',    
				array(
					'key' => 'grading',
					'value' => $params['type']
				),
				array(
					'key' => 'user_id',
					'value' => $params["user_id"]
				),
				array(
					'key' => 'status',
					'value' => 'pending'
				)
			),
			'post_type' => 'cards-grading-card',
			'posts_per_page' => -1
		);
		
		$total_dv = 0;
		$total_cards = 0;

		$posts = get_posts($args);

		foreach($posts as $post)
		{

			$card_data =  get_post_meta( $post->ID , 'card' , true );
			$card = json_decode($card_data, true);

			$total_cards = $total_cards + $card["quantity"];
			$total_dv = $total_dv + ( $card["quantity"] * $card["dv"] );
	
			update_post_meta($post->ID, 'status', 'checkout');   
			add_post_meta($post->ID, "checkout_id", $checkout_post_id );
			update_post_meta($post->ID, "status", "To Ship" );

		}

		add_post_meta($checkout_post_id, "total_dv", $total_dv );
		add_post_meta($checkout_post_id, "total_cards", $total_cards );
		add_post_meta($checkout_post_id, "status", $status );
		add_post_meta($checkout_post_id, "grading_orders_id", $lastid );
		


		if( $rows != false ){
			return ["error" => false, "params" => $params, "checkout_post_id" => $checkout_post_id];
		} else {
			return ["error" => true, "params" => $params, "checkout_post_id" => $checkout_post_id ];
		}


	}


	function confirmUploadGradingFile($params, $files) {
		
		$uploads = [];
		$upload_folder = wp_get_upload_dir();
		$upload_folder = $upload_folder["basedir"];
		$allowed_extensions = ["image/jpeg", "image/png", "application/pdf", "text/csv", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ];

		foreach($files as $k => $v){

			$extension = $v["type"];	
			
			if( in_array( $extension, $allowed_extensions ) ){


				$fileName = $k . '-' . $params["user_id"] . "-" . $params["type"] . "-" . rand( time() , 1000 ) . "-" .  $v["name"];

				$file = file_get_contents( $v["tmp_name"] );
	
				$filesize = file_put_contents( $upload_folder."/cards/".$fileName, $file );

				$v["baseurl"] = "/wp-content/uploads/cards/" . $fileName;
				$v["filename"] = $fileName;
				$v["upload_label"] = $k;
				$v["filesize"] = $filesize;		
				
				$v["qty"] = $params["qty"];
				$v["card_show"] = $params["card_show"];
				
				$uploads[] = $v;
		
			}

		}

		$this->wpdb->insert(
					'grading',
					array(							
						'user_id' => $params["user_id"],
						'type' => $params["type"] . "_file",
						"data" => json_encode($uploads),
					)
				);

		$lastid = $this->wpdb->insert_id;					

		return ["error"=> false, "data" => $uploads, "params" => $params];

	}

	// EBAY ROUTINES

	public function autoRefreshComplete(){

			$this->wpdb->insert(
				'autorefresh',
				array(
					'refresher' => "python",
				)
			);
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
		elseif( $type == "scheduled" ){
			$switch = "ScheduledList";
			$duration = '';
			$switch_filter = "";
			$sort = "Price";
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
				elseif( array_key_exists( "ScheduledList", $json ) ){

					$requestType = "ScheduledList";

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
					elseif( $type == "scheduled" ){
						$itemID = $item["ItemID"];
						$SKU = $item["SKU"];
						$itemstatus = "ScheduledList";
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
										'sku' => $SKU,
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
									'sku' => $SKU,
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
	
	public function getItemInfo( $item_id){

		$apiURL = "https://api.ebay.com/ws/api.dll";

		$post_data = 
		'<?xml version="1.0" encoding="utf-8"?>' .
		'<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">' .
			'<RequesterCredentials>' .
				'<eBayAuthToken>' . $this->access_token  . '</eBayAuthToken>' .
			'</RequesterCredentials>' .
			'<ItemID>' . $item_id . '</ItemID>' .
		'</GetItemRequest> ';
		

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
					'X-EBAY-API-COMPATIBILITY-LEVEL:967'
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


	function confirmPayoutRequest($params){


		$user_id = $params["user_id"];
		$cards = [];

			foreach( $params["card"] as $k => $c ) {
				array_push($cards, $c );
			} 


		$data = [
			"cards" => $cards,
			"remarks" => $params["remarks"],
			"cards_count" => $params["cards_count"],
			"requested_amount" => $params["requested_amount"],
			"payment_method" => $params["payment_method"],
			"paypal_email" => $params["paypal_email"],
			"bank_name" => $params["bank_name"],
			"bank_routing_number" => $params["bank_routing_number"],
			"bank_account_number" => $params["bank_account_number"],
			"name_on_bank_account" => $params["name_on_bank_account"],
		];

		$this->wpdb->insert(
			'payouts',
			array(
				'status' => "REQUESTED",
				'user_id' => $user_id,
				"data" => json_encode($data),
			)
		);

		$lastid = $this->wpdb->insert_id;

		$array = implode("','",$cards);

		$sql = "UPDATE ebay SET `request_id`  = " . $lastid . " WHERE item_id IN ('" . $array . "')";

		$updated_items = $this->wpdb->get_results ( $sql );

		return ["error" => false, "payout_id" => $lastid, "updated_items" => $updated_items];
	}

	function getPayoutRequest($params){



		$sql = "SELECT * FROM payouts WHERE id = '" . $params["payout_id"] . "'";
		$payout = $this->wpdb->get_results ( $sql );
		

		$data = json_decode( $payout[0]->data, true );
		$array = implode("','",$data["cards"]);

		$sql = "SELECT * FROM ebay WHERE item_id IN ('" . $array . "')";
		$cards = $this->wpdb->get_results ( $sql );


		$user_id = $payout[0]->user_id;
		$user_data = get_userdata($user_id);

		$user = [
			"id" => $user_data->data->ID,
			"name" => $user_data->data->display_name,
			"email" => $user_data->data->user_email,
		];

		return ["error" => false, "payout" => $payout , "cards" => $cards, "user" => $user ];
	}


	function confirmPayoutDone($params){



		$sql = "UPDATE payouts SET `status`  = 'PAID OUT' WHERE id = " . $params["payout_id"];
		$updated_items = $this->wpdb->get_results ( $sql );

		$sql = "UPDATE ebay SET `status`  = 'PaidOut', `paid_out_date` = CURDATE() WHERE request_id = " . $params["payout_id"];
		$paidout_items = $this->wpdb->get_results ( $sql );


		return ["error" => false, "payout_id" => $params["payout_id"], "paidout_items" => $paidout_items];
	}	
	

	function grading_checkbox($params){

		if( $params["check_action"] == "remove" ){

			$sql = "DELETE FROM grading_addons where type='". $params["type"] .  "' AND user_id = '". $params["user_id"] .  "'";
			$query = $this->wpdb->get_results ( $sql );	

			return true;
		}
		elseif( $params["check_action"] == "add" ){

			$this->wpdb->insert(
				'grading_addons',
				array(
					'user_id' => $params["user_id"],
					"type" => $params["type"],
				)
			);
	
			return true;

		} else {
			return $params;
		}

	}

	function remove_grading_file($params){

		$sql = "DELETE FROM grading  WHERE id = " . $params["id"];
		$deleted_items = $this->wpdb->get_results ( $sql );
		
		return true;

	}

	function card_grade_saving( $params ) {

        update_post_meta($params["post_id"], 'status', "Graded");   
        update_post_meta($params["post_id"], $params["name"], $params[$params["name"]]);   

		$sql = "SELECT * FROM grading WHERE id = " . $params["db_id"];
		$result = $this->wpdb->get_results ( $sql );

		$data = json_decode( $result[0]->data, true );
		$data[ $params["name"] ] = $params["value"];
		
		$sql = "UPDATE grading SET data = '" . json_encode($data) . "' WHERE id = " . $params["db_id"];
		$result = $this->wpdb->get_results ( $sql );

		$sql = "SELECT * FROM grading WHERE id = " . $params["db_id"];
		$result = $this->wpdb->get_results ( $sql );

		$data = json_decode( $result[0]->data, true );

		if( array_key_exists("grade", $data) && array_key_exists("certificate_number", $data)  ){

			if( $data["grade"] != ""  && $data["certificate_number"] != "" ){

				$grading_type =  explode( "-", get_post_meta( $params["post_id"] , 'grading' , true ));

				if( $grading_type[0] == "psa" ){

					$psa = $this->getPSA( $data["certificate_number"] );

					$psa_data = json_decode( $psa, true );
	
					if (json_last_error() === JSON_ERROR_NONE) {

						$psa_info = $psa_data["table_data"];

						$title = $psa_info["Year"] . " " .  $psa_info["Brand"] . " " .  $psa_info["Player"] . " " . $psa_info["Card Number"] . " " . $psa_info["Variety/Pedigree"] . " " . $psa_info["Grade"];
						$grade =  $data["grade"];
						$certificate_number =  $data["certificate_number"];

						$data[ "title"] = $title;
						$data[ "certImgBack"] = $psa_data["certImgBack"];
						$data[ "certImgFront"] = $psa_data["certImgFront"];


						$sql = "UPDATE grading SET data = '" . json_encode($data) . "' WHERE id = " . $params["db_id"];
						$result = $this->wpdb->get_results ( $sql );
				
						return [ "error" => true, "psa" => $psa_data, "title" => $title, "grade"=> $grade, "certificate_number" => $certificate_number, "certImgFront" => $psa_data["certImgFront"], "certImgBack" => $psa_data["certImgBack"] ];
						
					} else {
						return [ "error" => false ];
					}

				} else {

					return [ "error" => true ];

				}
	
			} else {
	
				return [ "error" => false ];
			}
	
		} else {
			return [ "error" => false ];
		}




	}

    public function getPSA($certificate_number){
        
        $response = array();
        $command = "python3 /home/arhizsx/psa.py " . $certificate_number . "  2>&1"; // Capture both stdout and stderr
        
        // Execute the Python script and capture output and errors
        $output = shell_exec($command);
    
        // Return the JSON response
        return $output;

    }


}