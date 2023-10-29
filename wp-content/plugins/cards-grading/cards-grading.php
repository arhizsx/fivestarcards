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

        // Add Assets
        add_action('wp_enqueue_scripts', array( $this, 'load_assets') );

        // Add Shortcodes
        add_shortcode('cards-grading', array( $this, 'cards_grading_shortcodes' ));

        // Add JS
        add_action('wp_footer', array( $this, 'load_scripts' ));

        // Add Endpoint
        add_action("rest_api_init", array($this, 'register_endpoint'));
    }

    public function create_custom_post_type()
    {
        // $args = array(
        //     'public' => true,
        //     'has_archive' => false,
        //     'supports' => array('title'),
        //     'exclude_from_search' => true,
        //     'publicly_queryable' => false,
        //     'capability' => 'manage_options',
        //     'labels' => array(
        //         'name' => 'Cards Grading',
        //         'singular_name' => 'Card Grading'
        //     ),
        //     'menu_icon' => 'dashicons-media-text'
        // );

        // register_post_type("cards-grading", $args);

        $args = array(
            'public' => true,
            'has_archive' => false,
            'supports' => array('title'),
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability' => 'manage_options',
            'labels' => array(
                'name' => 'Cards',
                'singular_name' => 'Card'
            ),
            'menu_icon' => 'dashicons-media-text',
            'supports' => ['custom-fields']
        );

        register_post_type("cards-grading-card", $args);


    }

    public function load_assets() {

        wp_enqueue_style(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'css/cards-grading.css',
            array(),
            1,
            'all'
        );

        wp_enqueue_script(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'js/cards-grading.js',
            array('jquery'),
            2,
            true
        );

    }

    public function subscribe_link_att($atts) {
        $default = array(
            'link' => '#',
        );
        $a = shortcode_atts($default, $atts);
        return 'Follow us on '.$a['link'];
    }    

    public function cards_grading_shortcodes($atts) 
    {

        $default = array(
            'title' => 'Grading Title',
            'type' => 'grading-tyoe'
        );
        
        $params = shortcode_atts($default, $atts);

        include( plugin_dir_path( __FILE__ ) . 'admin/modal.php' );

    }


    public function load_scripts()
    { ?>
        <script>

        </script>
    <?php }


    public function register_endpoint(){
        
        register_rest_route(
            "cards-grading/v1",
            "add-card",
            array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_add_card')
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

        $post_id = wp_insert_post([
            'post_type' => 'cards-grading-card',
            'post_title' => 'Card Grading',
            'post_status' => 'publish'
        ]);

        foreach( $params as $label => $value ){
            add_post_meta($post_id, $label, $value);
        }
        

    }

 }

 new CardsGrading;
