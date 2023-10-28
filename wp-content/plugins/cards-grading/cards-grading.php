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
        // add_action( 'init', array( $this, 'register_plugin_styles' ) ); // front and admin

        // Add Shortcodes
        add_shortcode('cards-grading', array( $this, 'cards_grading_shortcodes' ));
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
            'labels' => array(
                'name' => 'Cards Grading',
                'singular_name' => 'Card Grading'
            ),
            'menu_icon' => 'dashicons-media-text'
        );

        register_post_type("cards-grading", $args);

    }

    public function load_assets() {

        wp_enqueue_style(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'css/cards-grading.css',
            array(),
            200,
            'all'
        );

        wp_enqueue_script(
            'cards-grading',
            plugin_dir_url(__FILE__) . 'js/cards-grading.js',
            array('jquery'),
            200,
            true
        );

    }

    function cards_grading_shortcodes() 
    {

        include( plugin_dir_path( __FILE__ ) . 'admin/modal.php' );

    }



 }

 new CardsGrading;
