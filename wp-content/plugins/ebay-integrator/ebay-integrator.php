<?php 

/** 
* Plugin Name:       Ebay Integrator
 * Plugin URI:        https://www.techteam.ph/wordpress/plugins/ebay-integrator
 * Description:       Ebay Integrator Plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Aris Salvador
 * Author URI:        https://www.techteam.ph/about/aris
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       ebay-integrator
 * Domain Path:       /languages
 */

 if( !defined('ABSPATH') )
 {
    echo 'You are not allowed';
    exit;
 }

 class EbayIntegrator {
    
    public function __construct() 
    {

        // Create Custom Post Type
        add_action('init', array($this, 'create_custom_post_type') );        


        // Add Assets
        add_action('wp_enqueue_scripts', array( $this, 'load_assets') );

        // Add JS
        add_action('wp_footer', array( $this, 'load_scripts' ));

        // Add Endpoint
        add_action("rest_api_init", array($this, 'register_endpoint'));


    }

    public function load_assets() 
    {

        wp_enqueue_style(
            'ebay-integrator',
            plugin_dir_url(__FILE__) . 'css/ebay-integrator.css',
            array(),
            10,
            'all'
        );

        wp_enqueue_script(
            'ebay-integrator',
            plugin_dir_url(__FILE__) . 'js/ebay-integrator.js',
            array('jquery'),
            167,
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

    public function create_custom_post_type()
    {
        $args = array(
            'public' => false,
            'has_archive' => false,
            'supports' => array('title'),
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability' => 'manage_options',
			'labels'      => array(
				'name'          => __( 'Ebay Integrator', 'textdomain' ),
				'singular_name' => __( 'Ebay Integrator', 'textdomain' ),
			),            
            'menu_icon' => 'dashicons-media-text',
            'supports' => ['custom-fields']
        );

        register_post_type("ebay-integrator", $args);
    }


    public function register_endpoint()
    {
        
        register_rest_route(
            "ebay-integrator/v1",
            "request",
            array(
                'methods' => 'GET',
                'callback' => array($this, 'handle_request')
            )                        
        );
    }

    private function handle_request( $data ){

        $headers = $data->get_headers();
        $params = $data->get_params();
        $nonce = $headers["x_wp_nonce"][0];

        if( !wp_verify_nonce($nonce, 'wp_rest') ){
            return new WP_REST_Response("Invalid Nonce", 422);
        }

        return $params;

    }
 }

 