/**
 * Plugin Template admin js.
 *
 *  @package Ebay Integration/JS
 */

jQuery( document ).ready(
	function ( e ) {

	}
);

jQuery( document ).on("click", ".ebayintegration-btn", function(){
	console.log( jQuery(this).data("action") );
});