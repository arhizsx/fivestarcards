<?php 

/** 
 * Plugin Name:       Cards Grading
 * Plugin URI:        https://www.techteam.ph/wordpress/plugins/cards-grading
 * Description:       Card Grading Plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Aris Salvador
 * Author URI:        https://www.techteam.ph/about/aris
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       cards-grading
 * Domain Path:       /languages
 */

 if( !defined('ABSPATH') )
 {
    echo 'You are not allowed';
    exit;
 }
 require_once ( 'dompdf/autoload.inc.php');
 use Dompdf\Dompdf; 
 use Dompdf\Options;

 class CardsGrading {

    public $wpdb;	
    
    public function __construct() 
    {

        global $wpdb;
		$this->wpdb = $wpdb;


        // Create Custom Post Type
        add_action('init', array($this, 'create_custom_post_type') );        

        // Customize Grding Post Type Columns
        add_filter( 'manage_cards-grading-card_posts_columns', array($this, 'add_cards_grading_card_columns'));
        add_action( 'manage_cards-grading-card_posts_custom_column' , array($this, 'custom_cards_grading_card_column'), 10, 2 );

        add_filter( 'manage_cards-grading-chk_posts_columns', array($this, 'add_cards_grading_checkout_columns'));
        add_action( 'manage_cards-grading-chk_posts_custom_column' , array($this, 'custom_cards_grading_checkout_column'), 10, 2 );

        
        // Add Assets
        add_action('wp_enqueue_scripts', array( $this, 'load_assets') );

        // Add Shortcodes
        add_shortcode('cards-grading', array( $this, 'cards_grading_shortcode' ));

        add_shortcode('cards-grading-admin', array( $this, 'cards_grading_admin_shortcode' ));

        add_shortcode('cards-grading-checkout', array( $this, 'cards_grading_checkout_shortcode' ));


        add_shortcode('cards-grading-dashbox_cards', array( $this, 'cards_grading_dashbox_cards_shortcode' ));
        add_shortcode('cards-grading-dashbox_orders', array( $this, 'cards_grading_dashbox_orders_shortcode' ));

        // Layout

        add_shortcode('cards-grading-my_account', array( $this, 'cards_grading_my_account_shortcode' ));
        
        // Tables

        add_shortcode('cards-grading-orders_table', array( $this, 'cards_grading_orders_table_shortcode' ));
        
        // Views

        add_shortcode('cards-grading-view_order', array( $this, 'cards_grading_view_order_shortcode' ));


        // Add JS
        add_action('wp_footer', array( $this, 'load_scripts' ));

        // Add Endpoint
        add_action("rest_api_init", array($this, 'register_endpoint'));

    }

    public function load_assets() 
    {

        wp_enqueue_style(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'css/cards-grading.css',
            array(),
            10,
            'all'
        );

        wp_enqueue_script(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'js/cards-grading.js',
            array('jquery'),
            188,
            true
        );

    }

    public function load_scripts()
    {   
        ?>
        <script>

        </script>
        <?php 
    }

    //*********** POST TYPES *********** //


    public function create_custom_post_type()
    {
        $args = array(
            'public' => true,
            'has_archive' => false,
            'supports' => array('title'),
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability' => 'manage_options',
			'labels'      => array(
				'name'          => __( 'CG Cards', 'textdomain' ),
				'singular_name' => __( 'CG Card', 'textdomain' ),
			),            
            'menu_icon' => 'dashicons-media-text',
            'supports' => ['custom-fields']
        );

        register_post_type("cards-grading-card", $args);

        $args = array(
            'public' => true,
            'has_archive' => false,
            'supports' => array('title'),
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability' => 'manage_options',
			'labels'      => array(
				'name'          => __( 'CG Checkouts', 'textdomain' ),
				'singular_name' => __( 'CG Checkout', 'textdomain' ),
			),            
            'menu_icon' => 'dashicons-media-text',
            'supports' => ['custom-fields']
        );

        register_post_type("cards-grading-chk", $args);

        $args = array(
            'public' => true,
            'has_archive' => false,
            'supports' => array('title'),
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability' => 'manage_options',
			'labels'      => array(
				'name'          => __( 'CG Types', 'textdomain' ),
				'singular_name' => __( 'CG Type', 'textdomain' ),
			),            
            'menu_icon' => 'dashicons-media-text',
            'supports' => ['custom-fields']
        );

        register_post_type("cards-grading-type", $args);


        $this->setup_grading_types();

    }

    public function setup_grading_types(){
        
        $fivestar_grading_types = [
            [
                "name" => "PSA - Value Bulk",
                "type" => "psa-value_bulk",
                "per_card" => 19,
                "max_dv" => 499,
                "url" => '/grading/psa/value-bulk',
            ],
            [
                "name" => "PSA - Value Plus",
                "type" => "psa-value_plus",
                "per_card" => 40,
                "max_dv" => 499,
                "url" => '/grading/psa/value-plus',
            ],
            [
                "name" => "PSA - Regular",
                "type" => "psa-regular",
                "per_card" => 75,
                "max_dv" => 1499,
                "url" => '/grading/psa/regular',
            ],
            [
                "name" => "PSA - Express",
                "type" => "psa-express",
                "per_card" => 165,
                "max_dv" => 2499,
                "url" => '/grading/psa/express',
            ],
            [
                "name" => "PSA - Super Express",
                "type" => "psa-super_express",
                "per_card" => 330,
                "max_dv" => 4999,
                "url" => '/grading/psa/super-express',
            ],
            [
                "name" => "SGC - Bulk",
                "type" => "sgc-bulk",
                "per_card" => 15,
                "max_dv" => 1500,
                "url" => '/grading/sgc/bulk',
            ]

        ];

        foreach( $fivestar_grading_types as $default_grading){

            $args = array(
                'meta_query' => array(
                    array(
                        'key' => 'type',
                        'value' => $default_grading["type"]
                    )
                ),
                'post_type' => 'cards-grading-type',
                'posts_per_page' => -1
            );
            
            $old_posts = get_posts($args);
            
            if( ! $old_posts ){

                $post_id = wp_insert_post([
                    'post_type' => 'cards-grading-type',
                    'post_title' => $default_grading["name"],
                    'post_status' => 'publish'
                ]);
        
                add_post_meta($post_id, "name", $default_grading["name"] );
                add_post_meta($post_id, "type", $default_grading["type"] );
                add_post_meta($post_id, "per_card", $default_grading["per_card"] );
                add_post_meta($post_id, "max_dv", $default_grading["max_dv"] );
                add_post_meta($post_id, "url", $default_grading["url"] );
    
            } else {

                update_post_meta($old_posts[0]->ID, "name", $default_grading["name"] );
                update_post_meta($old_posts[0]->ID, "type", $default_grading["type"] );
                update_post_meta($old_posts[0]->ID, "per_card", $default_grading["per_card"] );
                update_post_meta($old_posts[0]->ID, "max_dv", $default_grading["max_dv"] );
                update_post_meta($old_posts[0]->ID, "url", $default_grading["url"] );
                
            }    
        }

    }

    function add_cards_grading_card_columns($columns) {
        return array_merge(
                $columns,
                array(
                    'user_id' => __('User'),
                    'status' => __('Status'),
                    'grading' =>__( 'Grading'),
                    'quantity' =>__( 'Quantity'),
                    'dv' =>__( 'DV')
                )
                
            );
    }
    
    function custom_cards_grading_card_column( $column, $post_id ) {
        
        $card_data =  get_post_meta( $post_id , 'card' , true );
        $card = json_decode($card_data, true);

        switch ( $column ) {
          case 'user_id':
            $user_id = get_post_meta( $post_id , 'user_id' , true );
            $user = get_user_by( "id", $user_id );

            echo $user->display_name;

            break;
          case 'quantity':
            echo $card["quantity"];
            break;
          case 'dv':
            echo $card["dv"];
            break;

          case 'grading':
            echo get_post_meta( $post_id , 'grading' , true );
            break;
          case 'status':
            echo get_post_meta( $post_id , 'status' , true );
            break;
        }
    }

    function add_cards_grading_checkout_columns($columns) {
        return array_merge(
                $columns,
                array(
                    'user_id' => __('User'),
                    'status' => __('Status'),
                )
                
            );
    }


    function custom_cards_grading_checkout_column( $column, $post_id ) {
        
        $card_data =  get_post_meta( $post_id , 'chk' , true );
        $card = json_decode($card_data, true);

        switch ( $column ) {
          case 'user_id':
            $user_id = get_post_meta( $post_id , 'user_id' , true );
            $user = get_user_by( "id", $user_id );

            echo $user->display_name;

            break;

          case 'status':
            echo get_post_meta( $post_id , 'status' , true );
            break;
        }
    }
    //*********** POST TYPES *********** //


    //*********** SHORTCODES *********** //

    public function cards_grading_shortcode($atts) 
    {

        $default = array(
            'title' => 'Grading Title',
            'type' => 'grading-tyoe'
        );
        
        $params = shortcode_atts($default, $atts);

        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'admin/modal.php');
        
        $output = ob_get_clean(); 
        
        return $output ;
    }

    public function cards_grading_my_account_shortcode($atts) 
    {

        $default = array(
            'title' => 'Grading Title',
            'type' => 'grading-tyoe'
        );
        
        $params = shortcode_atts($default, $atts);

        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'members/my_account.php');
        
        $output = ob_get_clean(); 
        
        return $output ;
    }


    public function cards_grading_admin_shortcode($atts) 
    {

        $default = array(
            'title' => 'Add Order to Customer',
            'type' => 'grading-tyoe'
        );
        
        $params = shortcode_atts($default, $atts);
        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'admin/grading_table_admin.php' );
        
        $output = ob_get_clean(); 
        
        return $output ;
    }

    public function cards_grading_checkout_shortcode($atts) 
    {
        $type = $_GET['type'];

        $default = array(
            'title' => 'Checkout',
            'type' => $type
        );
        
        $params = shortcode_atts($default, $atts);
        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'admin/checkout.php' );
        
        $output = ob_get_clean(); 
        
        return $output ;
    }

    public function cards_grading_orders_table_shortcode($atts) 
    {

        $default = array(
            'title' => 'Orders Table Shortcode',
            'table' => '',
            'type' => 'admin'
        );
        
        $params = shortcode_atts($default, $atts);

        ob_start();


        switch( $atts['table'] ){

            case "open_orders":
                $folder = "admin";
                $table = 'open_orders.php';
                break;

            case "order_receiving":
                $folder = "admin";
                $table = 'order_receiving.php';
                break;

            case "consigned_orders":
                $folder = "admin";
                $table = 'consigned_orders.php';
                break;

            case "consigned_for_payment":
                $folder = "admin";
                $table = 'consigned_for_payment.php';
                break;

            case "awaiting_payment":
                $folder = "admin";
                $table = 'awaiting_payment.php';
                break;

            case "completed_orders":
                $folder = "admin";
                $table = 'completed_orders.php';
                break;

            case "cancelled_orders":
                $folder = "admin";
                $table = 'cancelled_orders.php';
                break;

            case "my_completed":
                $folder = "members";
                $table = 'my_completed.php';
                break;

            case "my_consigned":
                $folder = "members";
                $table = 'my_consigned.php';
                break;

            case "my_for_payment":
                $folder = "members";
                $table = 'my_for_payment.php';
                break;

            case "my_orders":
                $folder = "members";
                $table = 'my_orders.php';
                break;

            case "members":
                $folder = "admin";
                $table = 'members.php';
                break;

            default:
                $table = '';

        }


        include( plugin_dir_path( __FILE__ ) . $folder . '/tables/'  . $table );
        
        $output = ob_get_clean(); 
        
        return $output ;
    }

    public function cards_grading_view_order_shortcode($atts) 
    {
        $order_number = $_GET['id'];

        $default = array(
            'title' => 'Order Number',
            'order_number' => $order_number,
            'view' => 'admin_view_order.php'
        );
        
        $params = shortcode_atts($default, $atts);
        ob_start();


        switch( $atts['view'] ){

            case "admin_view_order":
                $folder = "admin";
                $view = 'admin_view_order.php';
                break;
            
            case "admin_view_consignment":
                $folder = "admin";
                $view = 'admin_view_consignment.php';
                break;
            
            case "admin_view_completed":
                $folder = "admin";
                $view = 'admin_view_completed.php';
                break;
            
            case "admin_view_payment":
                $folder = "admin";
                $view = 'admin_view_payment.php';
                break;
            
            case "view_order":
                $folder = "members";
                $view = 'view_order.php';
                break;
            
            default:
        }

        include( plugin_dir_path( __FILE__ ) . $folder .'/views/' . $view );
        
        $output = ob_get_clean(); 
        
        return $output ;
    }

    public function cards_grading_dashbox_orders_shortcode($atts) 
    {
        $default = array(
            'type' => "awaiting_payment"
        );
        
        $params = shortcode_atts($default, $atts);
        ob_start();

        $what_array = null;

        if( $params['type'] == "awaiting_payment" ){
            $what_array = array("Order To Pay");
        }
        elseif( $params['type'] == "order_receiving" ){
            $what_array = array("To Ship", "Shipped", "Package Received", "Incomplete Items Shipped");
        }
        elseif( $params['type'] == "consigned_orders" ){
            $what_array = array("Order Consigned", "Order Partial Consignment", "Ready For Payment");
        }
        elseif( $params['type'] == "for_payment" ){
            $what_array = array("Ready For Payment");
        }
        elseif( $params["type"] == "members"){

            $args = array(
                'orderby'    => 'display_name',
                'order'      => 'ASC'
            );

            $users = get_users( $args );

            $total_users = 0;

            if($users){
                foreach($users as $user){
                    if($user->roles[0] == "um_member"){
                        $total_users++;
                    }
                }
            }

            return $total_users;

        }
                        
        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'status',
                    'value' => $what_array,
                    'compare' => "IN"
                )
            ),
            'post_type' => 'cards-grading-chk',
            'posts_per_page' => -1
        );

        $posts = get_posts($args);

        echo count($posts);

        $output = ob_get_clean(); 
        
        return $output ;

    }
    public function cards_grading_dashbox_cards_shortcode($atts) 
    {

        $default = array(
            'type' => "incoming_cards"
        );
        
        $params = shortcode_atts($default, $atts);
        ob_start();

        $what_array = null;

        if( $params['type'] == "incoming_cards" ){
            $what_array = array("To Ship", "Shipped");
        }
        elseif( $params['type'] == "processing" ){
            $what_array = array("Received", "Processing");
        }
        elseif( $params['type'] == "graded" ){
            $what_array = array("Graded", "Consign Card", "Consigned", "To Pay - Grade Only", "Sold - Consigned");
        }
        elseif( $params['type'] == "consigned" ){
            $what_array = array("Consign Card", "Consigned", "Sold - Consigned");
        }
        elseif( $params['type'] == "sold" ){
            $what_array = array("Sold - Consigned");
        }
                        
        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'status',
                    'value' => $what_array,
                    'compare' => "IN"
                )
            ),
            'post_type' => 'cards-grading-card',
            'posts_per_page' => -1
        );

        $posts = get_posts($args);

        echo count($posts);

        $output = ob_get_clean(); 
        
        return $output ;
    }


    //*********** SHORTCODES *********** //


    //*********** ENDPOINTS *********** //

    public function register_endpoint()
    {
        
        register_rest_route(
            "cards-grading/v1",
            "add-card",
            array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_add_card')
            )                        
        );

        register_rest_route(
            "cards-grading/v1",
            "table-action",
            array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_table_action')
            )                        
        );

        register_rest_route(
            "cards-grading/v1",
            "order-action",
            array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_order_action')
            )                        
        );

        register_rest_route(
            "cards-grading/v1",
            "pdf",
            array(
                'methods' => 'GET',
                'callback' => array($this, 'handle_pdf')
            )                        
        );

        register_rest_route(
            "cards-grading/v1",
            "makepdf",
            array(
                'methods' => 'GET',
                'callback' => array($this, 'handle_make_pdf')
            )                        
        );

        register_rest_route(
            "cards-grading/v1",
            "notification",
            array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_notification')
            )                        
        );

    }

    public function handle_notification($data){
        $body = $data->get_body();

        libxml_use_internal_errors(true);
        
        $xml = simplexml_load_string($body);
        
        if (false === $xml) {
            $errors = libxml_get_errors();
            echo 'Errors are '.var_export($errors, true);
            throw new \Exception('invalid XML');
        } 
        
        
        return $xml;

                
    }

    //*********** ENDPOINTS *********** //


    //*********** HANDLERS *********** //
    
    public function handle_add_card($data){

        $headers = $data->get_headers();
        $params = $data->get_params();
        $nonce = $headers["x_wp_nonce"][0];

        if( !wp_verify_nonce($nonce, 'wp_rest') ){
            return new WP_REST_Response("Invalid Nonce", 422);
        }

        $user_id = get_current_user_id();
        $user = get_user_by( "id", $user_id ); 


        $post_id = wp_insert_post([
            'post_type' => 'cards-grading-card',
            'post_title' => $user->display_name . " - " . $params["player"],
            'post_status' => 'publish'
        ]);

        add_post_meta($post_id, "checkout_id", $params["checkout_id"] );
        add_post_meta($post_id, "user_id", $params["user_id"] );
        add_post_meta($post_id, "grading", $params["grading"] );
        add_post_meta($post_id, "status", "pending" );
        add_post_meta($post_id, "card", json_encode($params) );


        
        return $post_id;
    }

    public function handle_table_action($data){

        $headers = $data->get_headers();
        $nonce = $headers["x_wp_nonce"][0];

        if( !wp_verify_nonce($nonce, 'wp_rest') ){
            return new WP_REST_Response("Invalid Nonce", 422);
        }

        $params = $data->get_params();

        if($params["action"] == "clear"){

            return $this->doClearTable($params);


        }
        elseif($params["action"] == "delete"){

            return $this->doDeleteCard($params);

        }
        elseif($params["action"] == "update"){

            return $this->doUpdateCard($params);

        }
        elseif($params["action"] == "checkout"){

            return $this->doCheckout($params);

        }
        elseif($params["action"] == "make_admin"){

            return $this->doMakeAdmin($params);

        }
        elseif($params["action"] == "demote_admin"){

            return $this->doDemoteAdmin($params);

        }
        elseif($params["action"] == "view_account"){

            return $this->doViewAccount($params);
            

        }

        return $params;

    }

    public function doViewAccount($params){

        // DO VIEW ACCOUNT NEXT

        return "TESTING";
    }

    public function handle_order_action($data){

        $headers = $data->get_headers();
        $nonce = $headers["x_wp_nonce"][0];

        if( !wp_verify_nonce($nonce, 'wp_rest') ){
            return new WP_REST_Response("Invalid Nonce", 422);
        }

        $params = $data->get_params();
        

        if($params["action"] == "set_shipping"){

            return $this->doSetShipping($params);

        }
        elseif($params["action"] == "card_update_status"){

            return $this->doCardUpdateMeta($params, "status");

        }
        
        elseif($params["action"] == "package_received"){

            return $this->doPackageReceived($params);

        }
        elseif($params["action"] == "complete_package_contents"){

            return $this->doPackageCompleteItems($params);

        }
        elseif($params["action"] == "acknowledge_missing_cards"){

            return $this->doPackageCompleteItems($params);

        }
        elseif($params["action"] == "incomplete_package_contents"){

            return $this->doPackageIncompleteItems($params);

        }
        elseif($params["action"] == "show_grades"){

            return $this->doShowGrades($params);

        }

        elseif($params["action"] == "set_grade"){

            return $this->doSetGrade($params);

        }

        elseif($params["action"] == "pay_card_grading"){

            return $this->doPayCardGrading($params);

        }

        elseif($params["action"] == "consign_card"){

            return $this->doConsignCard($params);

        }

        elseif($params["action"] == "complete_grading_process"){

            return $this->doCompleteGradingProcess($params);

        }

        elseif($params["action"] == "acknowledge_order_request"){

            return $this->doAcknowledgeOrderRequest($params);

        }

        elseif($params["action"] == "confirm_sold_price"){

            return $this->doSetSoldPrice($params);

        }

        elseif($params["action"] == "consignment_ready_for_payment"){

            return $this->doConsignmentReadyForPayment($params);

        }

        elseif($params["action"] == "confirm_consignment_payment"){

            return $this->doConfirmConsignmentPayment($params);

        }
        
        elseif($params["action"] == "cancel_order"){

            return $this->doCancelOrder($params);

        }
        
        elseif($params["action"] == "confirm_payment_info"){

            return $this->doConfirmPaymentInfo($params);

        }
        
        elseif($params["action"] == "confirm_new_order_status"){

            return $this->doConfirmNewOrderStatus($params);

        }

        elseif($params["action"] == "confirm_submission_number"){

            return $this->doConfirmSubmissionNumber($params);

        }

        elseif($params["action"] == "confirm_admin_delete_order"){

            return $this->doConfirmAdminDeleteOrder($params);

        }

        elseif($params["action"] == "confirm_admin_delete_manual_order"){

            return $this->doConfirmAdminDeleteOrder($params); 

        }

        elseif($params["action"] == "multi_update_status"){

            return $this->doMultiOrderUpdate($params);

        }

        elseif($params["action"] == "admin_create_order"){

            return $this->doAdminCreateOrder($params);

        }

        elseif( $params["action"] == "admin_assign_order" ){

            return $this->doAdminAssignOrder($params);

        }
            

        return $params;




    }    

    public function handle_make_pdf($data){

        $headers = $data->get_headers();
        $params = $data->get_params();


        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        $dompdf = new Dompdf($options);
        
        if($params["action"] == "payout_pdf_member"){

            $template = "payout_request_member.php";
            $file_prepend = "Payout Request";
            
            $data_pull = $this->getPayoutMember( $params["key"] );

            $data = $data_pull;

        }
        elseif($params["action"] == "payout_pdf_admin"){
            $template = "payout_request_admin.php";
            $file_prepend = "Payout Request";
            $data = [
                "title" => "Test Title",
                "content" => "Test Content",
                "data" => "Test Date",
            ];

        } else {
            $template = "data.php";
            $file_prepend = "";
            $data = [
                "title" => "X",
                "content" => "X",
                "data" => "X",
            ];
        }

        ob_start();
        include plugin_dir_path(__FILE__) . "pdf/" .  $template ; // Adjust path if needed
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf( $options );
    
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->loadHtml($html);
                
        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream("5 Star Cards - ". $file_prepend . ".pdf");
            
        return true;

    }

    function getPayoutMember($params){


		$user_id = $params["payout_id"];

		$sql = "SELECT * FROM payouts WHERE id = '" . $params["payout_id"] . "'";
		$payout = $this->wpdb->get_results ( $sql );

		$data = json_decode( $payout[0]->data, true );
		$array = implode("','",$data["cards"]);

		$sql = "SELECT * FROM ebay WHERE item_id IN ('" . $array . "')";
		$cards = $this->wpdb->get_results ( $sql );

		$sql = "SELECT * FROM ebay WHERE item_id IN ('" . $array . "')";
		$cards = $this->wpdb->get_results ( $sql );

		$user_data = get_userdata($user_id);

		$user = [
			"id" => $user_data->data->ID,
			"name" => $user_data->data->display_name,
			"email" => $user_data->data->user_email,
		];

		return ["error" => false, "payout" => $payout , "cards" => $cards, "user" => $user ];
	}



    public function handle_pdf($data){

        $order_number = $_GET["id"];

        $post = get_post($order_number);
        
        $post_meta = get_post_meta($post->ID);
        $user_id =  $post_meta["user_id"][0];
        $user = get_user_by( "id", $user_id );


        $customer = $user->display_name;
        
        $html = "<style>";

        
        $html .= " body { font-family: Arial, Helvetica, sans-serif; }";
        $html .= " table { width: 100%; border: 1px solid black; }";
        $html .= " table tr { border: 1px solid black; }";        
        $html .= " tr:nth-child(even) {";
        $html .= "   background-color: #f2f2f2;";
        $html .= " }";
        $html .= " logo { heigh: 30px; }";          
        $html .= "</style>";

        $html .= "<H1>5starcards.com</H1>";
        $html .= "<table'>";
        $html .= "  <tr>";
        $html .= "    <td>Customer Name</td>";
        $html .= "    <td>" .$user->display_name . "</td>";
        $html .= "  </tr>";
        $html .= "  <tr>";
        $html .= "    <td>Order #</td>";
        $html .= "    <td>" . $post_meta["order_number"][0] . "</td>";
        $html .= "  </tr>";
        $html .= "  <tr>";
        $html .= "    <td>Service Type</td>";
        $html .= "    <td>" . $post_meta["grading_type"][0] . "</td>";
        $html .= "  </tr>";
        $html .= "  <tr>";
        $html .= "    <td>Total Cards</td>";
        $html .= "    <td>" . $post_meta["total_cards"][0] . "</td>";
        $html .= "  </tr>";
        $html .= "</tr>";
        $html .= "</table>";

        $dompdf = new Dompdf();

        $dompdf->setPaper(array(0,0,500,230));

        $dompdf->loadHtml($html);
                
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser
        $dompdf->stream("5 Star Cards - ". $customer . " - ". $order_number . ".pdf");
            
        return true;
    }
    //*********** HANDLERS *********** //



    //*********** HANDLER FUNCTIONS *********** //


    public function doCardUpdateMeta($params, $key){
        update_post_meta($params["post_id"], $key, $params["value"] );
        return true;
    }

    public function doClearTable( $params ){

        try {

            $user_id = get_current_user_id();        

            $args = array(
                'meta_query' => array(
                    'relations' =>  'AND',    
                    array(
                        'key' => 'grading',
                        'value' => $params['type']
                    ),
                    array(
                        'key' => 'user_id',
                        'value' => $user_id
                    ),
                    array(
                        'key' => 'status',
                        'value' => 'pending'
                    )
                ),
                'post_type' => 'cards-grading-card',
                'posts_per_page' => -1
            );
            
            $posts = get_posts($args);
    
            foreach($posts as $post)
            {
                wp_delete_post( $post->ID, true );
            }
    
            return true;
    
        }
        catch (Exception $e) {
            return $e;
        }        
        
    }

    public function doCheckout($params){

        try {

            $user_id = get_current_user_id();        
            $user = get_user_by( "id", $user_id );

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

        
            add_post_meta($checkout_post_id, "user_id",  $user_id );
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
                        'value' => $user_id
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
            add_post_meta($checkout_post_id, "status", "To Ship" );
    
            return $checkout_post_id;
    
        }
        catch (Exception $e) {
            return $e;
        }        

    }

    public function doSetShipping($params){

        $order_number = $params["order_number"];

        update_post_meta($order_number, "status", "Shipped" );

        if( metadata_exists('post', $order_number, 'carrier') ){
            update_post_meta($order_number, "carrier", $params["data"]["carrier"] );
        } else {
            add_post_meta($order_number, "carrier", $params["data"]["carrier"] );
        }

        if( metadata_exists('post', $order_number, 'shipped_by') ){
            update_post_meta($order_number, "shipped_by", $params["data"]["shipped_by"] );
        } else {
            add_post_meta($order_number, "shipped_by", $params["data"]["shipped_by"] );
        }

        if( metadata_exists('post', $order_number, 'tracking_number') ){
            update_post_meta($order_number, "tracking_number", $params["data"]["tracking_number"] );
        } else {
            add_post_meta($order_number, "tracking_number", $params["data"]["tracking_number"] );
        }

        if( metadata_exists('post', $order_number, 'shipping_date') ){
            update_post_meta($order_number, "shipping_date", $params["data"]["shipping_date"] );
        } else {
            add_post_meta($order_number, "shipping_date", $params["data"]["shipping_date"] );
        }


        $args = array(
            'relations' =>  'AND',    
            'meta_query' => array(
                array(
                    'key' => 'status',
                    'value' => "To Ship"
                ),
                array(
                    'key' => 'checkout_id',
                    'value' => $order_number
                ),
            ),
            'post_type' => 'cards-grading-card',
            'posts_per_page' => -1
        );
        
        $posts = get_posts($args);

        foreach($posts as $post){

            $post_id = $post->ID;
            update_post_meta($post_id, "status", "Shipped" );

        } 
                

        return true;

    }

    public function doPackageReceived($params){

        update_post_meta($params["order_number"], 'status', 'Package Received');   
        return true;

    }


    public function doPackageCompleteItems($params){

        update_post_meta($params["order_number"], 'status', 'Processing Order');   
        return true;

    }

    public function doPackageIncompleteItems($params){

        update_post_meta($params["order_number"], 'status', 'Incomplete Items Shipped');   
        return true;

    }

    public function doShowGrades($params){

        update_post_meta($params["order_number"], 'status', 'Cards Graded');   
        return true;

    }

    public function doSetGrade($params){

        update_post_meta($params["post_id"], 'grade', $params["value"]);   
        update_post_meta($params["post_id"], 'status', "Graded");   
        return true;

    }

    public function doPayCardGrading($params){

        update_post_meta($params["post_id"], 'status', 'Pay Grading');   
        return true;

    }
    
    public function doConsignCard($params){

        update_post_meta($params["post_id"], 'status', 'Consign Card');   
        return true;

    }

    public function doCompleteGradingProcess($params){

        update_post_meta($params["order_number"], 'status', 'Grading Complete');   
        return true;

    }

    public function doAcknowledgeOrderRequest($params){

        $order_number = $params["order_number"];

        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'checkout_id',
                    'value' => $params['order_number']
                )
            ),
            'post_type' => 'cards-grading-card',
            'posts_per_page' => -1
        );
        
        $posts = get_posts($args);

        $pay_grading = 0;
        $consign_card = 0;

        foreach($posts as $post)
        {

            switch( get_post_meta( $post->ID , 'status' , true ) ){

                case "Pay Grading":

                    $pay_grading++;
                    update_post_meta($post->ID, 'status', 'To Pay - Grade Only');   
    
                    break;
                
                case "Consign Card":

                    $consign_card++;
                    update_post_meta($post->ID, 'status', 'Consigned');   
                    break;

                default:

            }

        }

        if( $pay_grading > 0 && $consign_card > 0){
            $status = "Order Partial Consignment";
        } 
        elseif( $pay_grading > 0 && $consign_card == 0){
            $status = "Order To Pay";
        }
        elseif( $pay_grading == 0 && $consign_card > 0){
            $status = "Order Consigned";
        }

        update_post_meta($params["order_number"], 'status', $status);   

        return true;


    }

    public function doSetSoldPrice($params){

        update_post_meta($params["post_id"], "status", "Sold - Consigned" );

        update_post_meta($params["post_id"], "sold_price", $params["value"] );

        $to_receive = 0;

        if( $params["value"] < 10 ){

            $to_receive = $params["value"] - 3;

        }
        elseif( $params["value"] >= 10 && $params["value"] <= 49.99 ){

            $to_receive = $params["value"] - $params["value"] * 0.18;
            
        }
        elseif( $params["value"] >= 50 && $params["value"] <= 99.99 ){
            
            $to_receive = $params["value"] - $params["value"] * 0.16;

        }
        elseif( $params["value"] >= 100 && $params["value"] <= 199.99 ){
            
            $to_receive = $params["value"] - $params["value"] * 0.15;

        }
        elseif( $params["value"] >= 200 && $params["value"] <= 499.99 ){
            
            $to_receive = $params["value"] - $params["value"] * 0.14;

        }
        elseif( $params["value"] >= 500 && $params["value"] <= 999.99 ){
            
            $to_receive = $params["value"] - $params["value"] * 0.13;

        }
        elseif( $params["value"] >= 1000 && $params["value"] <= 2999.99 ){
            
            $to_receive = $params["value"] - $params["value"] * 0.12;

        }
        elseif( $params["value"] >= 3000 && $params["value"] <= 4999.99 ){

            $to_receive = $params["value"] - $params["value"] * 0.10;

        }
        elseif( $params["value"] >= 5000 && $params["value"] <= 8999.99  ){

            $to_receive = $params["value"] - $params["value"] * 0.08;
            
        }
        elseif( $params["value"] >= 9000  ){

            $to_receive = $params["value"] - $params["value"] * 0.07;
            
        }

        update_post_meta($params["post_id"], "to_receive", $to_receive );

        return true;

    }

    public function doConsignmentReadyForPayment($params){

        update_post_meta($params["order_number"], 'status', 'Ready For Payment');   
        return true;


    }

    public function doConfirmConsignmentPayment($params){

        update_post_meta($params["order_number"], 'status', 'Consignment Paid');   

        update_post_meta($params["order_number"], "consignment_mode_of_payment", $params["data"]["mode_of_payment"] );
        update_post_meta($params["order_number"], "consignment_paid_by", $params["data"]["paid_by"] );
        update_post_meta($params["order_number"], "consignment_payment_date", $params["data"]["payment_date"] );
        update_post_meta($params["order_number"], "consignment_reference_number", $params["data"]["reference_number"] );

        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'checkout_id',
                    'value' => $params['order_number']
                )
            ),
            'post_type' => 'cards-grading-card',
            'posts_per_page' => -1
        );
        
        $posts = get_posts($args);


        foreach($posts as $post)
        {

            switch( get_post_meta( $post->ID , 'status' , true ) ){

                case "To Pay - Grade Only":

                    update_post_meta($post->ID, 'status', 'Deducted - Grade Only');   
    
                    break;
                
                default:

            }

        }

        return true;
        
    }

    public function doCancelOrder($params){

        update_post_meta($params["order_number"], 'status', 'Order Cancelled');   

        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'checkout_id',
                    'value' => $params['order_number']
                )
            ),
            'post_type' => 'cards-grading-card',
            'posts_per_page' => -1
        );
        
        $posts = get_posts($args);


        foreach($posts as $post)
        {
            $status = get_post_meta( $post->ID , 'status' , true );

            switch( $status  ){

                case "Not Available":
    
                    break;
                
                default:

                    
                update_post_meta($post->ID, 'status', $status . " - Cancelled");   

            }

        }


        return true;

    }

    public function doConfirmPaymentInfo($params){

        update_post_meta($params["order_number"], 'status', 'Order Paid');   

        update_post_meta($params["order_number"], "order_mode_of_payment", $params["data"]["mode_of_payment"] );
        update_post_meta($params["order_number"], "order_paid_by", $params["data"]["paid_by"] );
        update_post_meta($params["order_number"], "order_payment_date", $params["data"]["payment_date"] );
        update_post_meta($params["order_number"], "order_reference_number", $params["data"]["reference_number"] );

        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'checkout_id',
                    'value' => $params['order_number']
                )
            ),
            'post_type' => 'cards-grading-card',
            'posts_per_page' => -1
        );
        
        $posts = get_posts($args);


        foreach($posts as $post)
        {

            switch( get_post_meta( $post->ID , 'status' , true ) ){

                case "To Pay - Grade Only":

                    update_post_meta($post->ID, 'status', 'Paid - Grade Only');   
    
                    break;
                
                default:

            }

        }
        
        return true;
    }

    public function doConfirmNewOrderStatus($params){

        if($params["data"]["new_status"] == ""){
            return "incomple data";
        }


        $user_id =  get_post_meta( $params["order_number"], 'user_id' , true );
        $user = get_userdata($user_id);
        

        update_post_meta($params["order_number"], 'status', $params["data"]["new_status"]);   

        $headers = array('Content-Type: text/html; charset=UTF-8');
        $to = $user->data->user_email;
        $body = "Hi " . $user->data->display_name . "<br><br>The status of your order is now " . $params["data"]["new_status"] ;
        $subject = "Order # ". $params["order_number"] . " Status Update";

        //Here put your Validation and send mail
        $sent = wp_mail($to, $subject, $body, $headers);
            
        if($sent) {
        //message sent!       
            return true;
        }
        else  {
            return "not sent";
        //message wasn't sent       
        }        

        return true;

    }

    public function doConfirmSubmissionNumber($params){

        if($params["data"]["submission_number"] == ""){
            return "incomple data";
        }

        update_post_meta($params["order_number"], 'submission_number', $params["data"]["submission_number"]);   
        return true;
    }

    public function doConfirmAdminDeleteOrder($params){


        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'checkout_id',
                    'value' => $params['order_number']
                )
            ),
            'post_type' => 'cards-grading-card',
            'posts_per_page' => -1
        );
        
        
        $posts = get_posts($args);

        foreach($posts as $post)
        {
            wp_delete_post( $post->ID, true );
        }

        wp_delete_post( $params['order_number'], true );

        return ['action' => 'back', "back" => $params["data"]["back"]] ;
    }

    public function doMultiOrderUpdate( $params)  {
        
        if($params["data"]["new_status"] == ""){
            return false;
        }

        foreach($params["order_number"] as $order){
            update_post_meta($order, 'status', $params["data"]["new_status"]);   
        }
        
        return true;
        
    }

    function doAdminCreateOrder($params)  {

        try {

            $user_id = $params["data"]["user_id"] - 1000;        
            $user = get_user_by( "id", $user_id );

            $args = array(
                'meta_query' => array(
                    array(
                        'key' => 'type',
                        'value' => $params["data"]["grading_type"]
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
        
            add_post_meta($checkout_post_id, "user_id",  $user_id );
            add_post_meta($checkout_post_id, "service_type", "Card Grading" );
            add_post_meta($checkout_post_id, "grading_type", $grading_name );
            add_post_meta($checkout_post_id, "order_number", $checkout_post_id );
            add_post_meta($checkout_post_id, "status", "Pending Customer Order" );
    
            return $checkout_post_id;
    
        }
        catch (Exception $e) {
            return $e;
        }        
        
        return $params;
    }

    function doAdminAssignOrder($params){

        update_post_meta($params["order_number"], 'status', "Processing Order");   

        

        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'checkout_id',
                    'value' => $params["order_number"]
                )
            ),
            'post_type' => 'cards-grading-card',
            'posts_per_page' => -1
        );

        $posts = get_posts($args);

        $total_cards = 0;
        $total_dv = 0;
 
        foreach($posts as $post)
        {
            update_post_meta( $post->ID, "status", "Received" );
            $total_cards = $total_cards + 1;
        }

        update_post_meta($params["order_number"], 'total_cards', $total_cards );   
        update_post_meta($params["order_number"], 'total_dv', $total_dv );   
        update_post_meta($params["order_number"], 'service_type', "Card Grading" );   

        return true;
    }

    function doDeleteCard($params){

        if($params["action"] == "delete"){

            wp_delete_post( $params["post_id"], true );
            return true;
        }   

        return $params;
    }

    function doUpdateCard($params){

        if($params["action"] == "update"){

            return $params;
        }   

        return $params;
    }

    function doMakeAdmin($params){

        $u = new WP_User( $params["user_id"] );

        // Remove role
        $u->remove_role( 'um_member' );
        
        // Add role
        $u->add_role( 'um_admin' );

        return true;

    }

    function doDemoteAdmin($params){

        $u = new WP_User( $params["user_id"] );

        // Remove role
        $u->remove_role( 'um_admin' );
        
        // Add role
        $u->add_role( 'um_member' );

        return true;

    }
    
    //*********** HANDLER FUNCTIONS *********** //

 }



 new CardsGrading;
