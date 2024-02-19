<?php
/**
 * Settings class file.
 *
 * @package Ebay Integration/Includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings class.
 */
class Ebay_Integration_Ebay_API {

	
	 public function __construct( ) {


	 }


	 public function create_ebay_enpoint( ){

		register_rest_route( '/ebayintegration/v1', '/request', array(
			'methods' => 'GET',
			'callback' => array( $this, 'handle_api_endpoint' )
		) );        

    }

     
}