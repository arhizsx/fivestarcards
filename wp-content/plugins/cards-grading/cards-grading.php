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
        // Add Plugin Menu
        add_action('admin_menu', array($this, 'plugin_menu'));

        // Create Custom Post Type
        add_action('init', array($this, 'create_custom_post_type') );        

        // Add Assets
        add_action('wp_enqueue_scripts', array( $this, 'load_assets') );

        // Add Shortcodes
        add_shortcode('cards-grading-list', array( $this, 'cards_grading_list' ));
    }

    public function plugin_menu()
    {
        add_menu_page(
            'Cards Grading',
            'Dashboard',
            'manage_options',
            'cards-grading',
            array($this, 'plugin_main_page'),
            'dashicons-media-spreadsheet'
        );

        add_submenu_page(
            'cards-grading',
            'Grading Types',
            'Grading Types',
            'manage_options',
            'cards-grading-types',
            array($this, 'plugin_grading_types_page'),
            'dashicons-media-spreadsheet'
        );

    }

    public function plugin_main_page()
    {
        include( plugin_dir_path( __FILE__ ) . 'admin/dashboard.php');        
    }

    public function plugin_grading_types_page()
    {
        include( plugin_dir_path( __FILE__ ) . 'admin/dashboard.php');        
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
            'cards-grading.css',
            plugin_dir_url(__FILE__) . '/css/cards-grading.css',
            array(),
            1,
            'all'
        );

        wp_enqueue_script(
            'cards-grading.js',
            plugin_dir_url(__FILE__) . '/js/cards-grading.js',
            array('jquery'),
            1,
            true
        );
        
    }

    public function cards_grading_list() {

        return 'test shortcode';
        
    }



 }

 new CardsGrading;
