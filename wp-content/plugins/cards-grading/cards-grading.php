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

        // Customize Post Type Columns
        add_filter( 'manage_cards-grading-card_posts_columns', array($this, 'add_cards_grading_card_columns'));
        add_action( 'manage_cards-grading-card_posts_custom_column' , array($this, 'custom_cards_grading_card_column'), 10, 2 );

        // Add Assets
        add_action('wp_enqueue_scripts', array( $this, 'load_assets') );

        // Add Shortcodes
        add_shortcode('cards-grading', array( $this, 'cards_grading_shortcode' ));
        add_shortcode('cards-grading-checkout', array( $this, 'cards_grading_checkout_shortcode' ));

        // Add JS
        add_action('wp_footer', array( $this, 'load_scripts' ));

        // Add Endpoint
        add_action("rest_api_init", array($this, 'register_endpoint'));

        

    }

    public function create_custom_post_type()
    {
        $labels = array(
            'name'               => _x( 'Cards Lists', 'mmd_list' ),
            'singular_name'      => _x( 'Card', 'mmd_lists' ),
            'add_new'            => _x( 'New Card', 'mmd_list' ),
            'add_new_item'       => __( 'Add New Card' ),
            'edit_item'          => __( 'Edit Card' ),
            'new_item'           => __( 'New Card' ),
            'all_items'          => __( 'Manage Cards' ),
            'view_item'          => __( 'View Cards' ),
            'search_items'       => __( 'Search Cards' ),
            'not_found'          => __( 'No Listing found' ),
            'not_found_in_trash' => __( 'No Listings found in the Trash' ), 
            'parent_item_colon'  => '',
            'menu_name'          => 'Card Grading'
        );        
        $args = array(
            'public' => true,
            'has_archive' => false,
            'supports' => array('title'),
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability' => 'manage_options',
            'capability' => 'manage_options',
            'labels' => $labels,
            'menu_icon' => 'dashicons-media-text',
            'supports' => ['custom-fields']
        );

        register_post_type("cards-grading-card", $args);

        add_submenu_page('edit.php?post_type=cards-grading-card',             // Parent Slug from add_menu_page 
                     'Dashboard',                     // Title of page
                     'Dashboard',                     // Menu title
                     'manage_options',               // Minimum capability to view the menu.
                     'cards-grading-settings',        // Unqiue Slug Name
                     'mmd_maplist_DrawAdminPage' 
        );  // A callback function used to display page content.        

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

            print_r ($user->display_name);

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
            3,
            'all'
        );

        wp_enqueue_script(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'js/cards-grading.js',
            array('jquery'),
            13,
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

        $default = array(
            'title' => 'Grading Title',
            'type' => 'grading-tyoe'
        );
        
        $params = shortcode_atts($default, $atts);
        ob_start();

        include( plugin_dir_path( __FILE__ ) . 'admin/checkout.php' );
        
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
                update_post_meta($post->ID, 'status', 'checkout');                
            }
    
            return true;
    
        }
        catch (Exception $e) {
            return $e;
        }        

    }

 }

 new CardsGrading;
