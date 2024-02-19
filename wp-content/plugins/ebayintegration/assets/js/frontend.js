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
			url: "/wp-json/ebayintegration/v1/ajax",
			data: { 
				action: "getItemPages"
			},
			success: function(resp){

				jQuery.ajax({
					method: 'get',
					url: "/wp-json/ebayintegration/v1/ajax",
					data: { 
						action: "getItems",
						page_number: 1
					},
					success: function(resp){
						jQuery(document).find(".ebayintegration-items_box").append(
							JSON.stringify(resp)					
						);
					},
					error: function(){
						console.log("Error in AJAX");
					}
				});

				jQuery.ajax({
					method: 'get',
					url: "/wp-json/ebayintegration/v1/ajax",
					data: { 
						action: "getItems",
						page_number: 2
					},
					success: function(resp){
						jQuery(document).find(".ebayintegration-items_box").append(
							JSON.stringify(resp)					
						);
					},
					error: function(){
						console.log("Error in AJAX");
					}
				});
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});

	
	}


});