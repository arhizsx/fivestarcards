/**
 * Plugin Template frontend js.
 *
 *  @package Ebay Integration/JS
 */

jQuery( document ).ready(
	function ( e ) {

	}
);

jQuery( document ).on("click", ".ebayintegration-btn", function(){

	if( jQuery(this).data("action") == "getItems" ){
	
		jQuery.ajax({
			method: 'get',
			url: "/wp-json/ebayintegration/v1/ajax?action=getItems",
			data: { 
				action: "getItems"
			},
			success: function(resp){
				console.log(resp);
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});

	
	}


});