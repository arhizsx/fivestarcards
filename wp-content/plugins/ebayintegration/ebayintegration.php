<?php
/**
 * Plugin Name: Ebay Integration
 * Version: 1.0.0
 * Plugin URI: http://www.techteam.ph/
 * Description: This is your starter template for your next WordPress plugin.
 * Author: Hugh Lashbrooke
 * Author URI: http://www.techteam.ph/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: ebayintegration
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Aris Salvador
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin class files.
require_once 'includes/class-ebayintegration.php';
require_once 'includes/class-ebayintegration-settings.php';

// Load plugin libraries.
require_once 'includes/lib/class-ebayintegration-admin-api.php';
require_once 'includes/lib/class-ebayintegration-post-type.php';
require_once 'includes/lib/class-ebayintegration-taxonomy.php';

/**
 * Returns the main instance of Ebay_Integration to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Ebay_Integration
 */
function Ebay_Integration() {
	$instance = Ebay_Integration::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Ebay_Integration_Settings::instance( $instance );
	}

	return $instance;
}

Ebay_Integration();