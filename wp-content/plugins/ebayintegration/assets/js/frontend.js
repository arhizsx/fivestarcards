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

				
				var loops = parseInt(resp["data"]);

				for(var i=1; i <= loops; i++){
					jQuery.ajax({
						method: 'get',
						url: "/wp-json/ebayintegration/v1/ajax",
						data: { 
							action: "getItems",
							page_number: i
						},
						success: function(resp){
							console.log(resp.data);
							jQuery.each(resp.data.ActiveList.ItemArray.Item, function(k, v){
								jQuery(document).find(".ebayintegration-items_box").append(
									JSON.stringify(v) + "<hr>"
								)
							});

						},
						error: function(){
							console.log("Error in AJAX");
						}
					});
	
				}


			},
			error: function(){
				console.log("Error in AJAX");
			}
		});

	
	}


});