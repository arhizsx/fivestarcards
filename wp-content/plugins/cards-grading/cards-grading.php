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
        add_shortcode('cards-grading', array( $this, 'cards_grading_shortcodes' ));

        // Load Javascript
        add_action('wp_footer', array($this, 'load_scripts'));
    }

    public function plugin_menu()
    {
        add_menu_page(
            'Cards Grading',
            'Cards Grading',
            'manage_options',
            plugin_dir_path( __FILE__ ) . 'admin/dashboard.php',
            null,
            'dashicons-media-spreadsheet'
        );
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


    function cards_grading_shortcodes( $atts = [], $content = null, $tag = '' ) {

        // normalize attribute keys, lowercase
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
    
        // override default attributes with user attributes
        $card_grading_atts = shortcode_atts(
            array(
                'title' => 'WordPress.org',
            ), $atts, $tag
        );
    
        // start box
        $o = '<div class="card-grading-box">';
    
        // title
        $o .= '<h2>' . esc_html( $card_grading_atts['title'] ) . '</h2>';
    
        if ( ! is_null( $content ) ) {
            // $content here holds everything in between the opening and the closing tags of your shortcode. eg.g [my-shortcode]content[/my-shortcode].
            // Depending on what your shortcode supports, you will parse and append the content to your output in different ways.
            // In this example, we just secure output by executing the_content filter hook on $content.



            $o .= apply_filters( 'the_content', $content );
        }
    
        // end box
        $o .= '</div>';
    
        // return output
        return $o;
    }

    public function load_scripts()
    { ?>

        <script>
            alert('Plugin');
        </script>

    <?php }


 }

 new CardsGrading;
