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


        add_action('rest_api_init', function() {
            register_rest_route('ebay-integrator/v1', 'notification', array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => 'handle_notification',
                'args' => array(),
                'permission_callback' => function () {
                return true;
                }
            ));
        });


    }

    public function handle_notification($data){
        return "TEST";
    }


 }