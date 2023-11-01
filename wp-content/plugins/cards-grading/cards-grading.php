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

 class CardsGrading {

    public function __construct() 
    {
        // Create Custom Post Type
        add_action('init', array($this, 'create_custom_post_type') );        

        // Customize Grding Post Type Columns
        add_filter( 'manage_cards-grading-card_posts_columns', array($this, 'add_cards_grading_card_columns'));
        add_action( 'manage_cards-grading-card_posts_custom_column' , array($this, 'custom_cards_grading_card_column'), 10, 2 );

        
        // Add Assets
        add_action('wp_enqueue_scripts', array( $this, 'load_assets') );

        // Add Shortcodes
        add_shortcode('cards-grading', array( $this, 'cards_grading_shortcode' ));
        add_shortcode('cards-grading-checkout', array( $this, 'cards_grading_checkout_shortcode' ));
        add_shortcode('cards-grading-my_orders', array( $this, 'cards_grading_my_orders_shortcode' ));
        add_shortcode('cards-grading-view_order', array( $this, 'cards_grading_view_order_shortcode' ));

        // Add JS
        add_action('wp_footer', array( $this, 'load_scripts' ));

        // Add Endpoint
        add_action("rest_api_init", array($this, 'register_endpoint'));

    }

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
    
    public function load_assets() 
    {

        wp_enqueue_style(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'css/cards-grading.css',
            array(),
            6,
            'all'
        );

        wp_enqueue_script(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'js/cards-grading.js',
            array('jquery'),
            18,
            true
        );

    }

    public function cards_grading_shortcode($atts) 
    {

        $default = array(
            'title' => 'Grading Title',
            'type' => 'grading-tyoe'
        );
        
        $params = shortcode_atts($default, $atts);
        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'admin/modal.php' );
        
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

    public function cards_grading_my_orders_shortcode($atts) 
    {
        $type = $_GET['type'];

        $default = array(
            'title' => 'Checkout',
            'type' => $type
        );
        
        $params = shortcode_atts($default, $atts);
        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'admin/my_orders.php' );
        
        $output = ob_get_clean(); 
        
        return $output ;
    }

    public function cards_grading_view_order_shortcode($atts) 
    {
        $order_number = $_GET['id'];

        $default = array(
            'title' => 'Order Number',
            'order_number' => $order_number
        );
        
        $params = shortcode_atts($default, $atts);
        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'admin/view_order.php' );
        
        $output = ob_get_clean(); 
        
        return $output ;
    }

    public function load_scripts()
    {   
        ?>
        <script>

        </script>
        <?php 
    }


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
    }

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
        elseif($params["action"] == "checkout"){

            return $this->doCheckout($params);

        }

        return $params;

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

            }

            add_post_meta($checkout_post_id, "total_dv", $total_dv );
            add_post_meta($checkout_post_id, "total_cards", $total_cards );
            add_post_meta($checkout_post_id, "status", "To Ship" );
    
            return true;
    
        }
        catch (Exception $e) {
            return $e;
        }        

    }

 }

 new CardsGrading;
