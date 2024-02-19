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
		
		jQuery(document).find(".ebayintegration-items_box").html("");

		jQuery.ajax({
			method: 'get',
			url: "/wp-json/ebayintegration/v1/ajax",
			data: { 
				action: "getItemPages"
			},
			success: function(resp){

				
				var loops = parseInt(resp["data"]);
				var items = [];

				for(var i=1; i <= loops; i++){
					jQuery.ajax({
						method: 'get',
						url: "/wp-json/ebayintegration/v1/ajax",
						data: { 
							action: "getItems",
							page_number: i
						},
						success: function(resp){
							jQuery.each(resp.data.ActiveList.ItemArray.Item, function(k, v){		
								items.push(v)					;
							});

						},
						error: function(){
							console.log("Error in AJAX");
						}
					});

				}
				console.log(items);

			},
			error: function(){
				console.log("Error in AJAX");
			}
		});

	
	}


});

function eBayItemTemplate(data){

	var template = "";

	template = "<div class='row mb-3 border-bottom' data-item_id=''>" +
					"<div class='col-lg-3 col-xl-3'>" + 
						"<img src='' class='item_img' />" +
					"</div>" + 
					"<div class='col-lg-9 col-xl-9'>"+
						"<div class='row'>" +
							"<div class='col-xl-12'>" + 
								"<label>Title</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.Title + "'/>" +
							"</div>" +
						"</div>" + 
						"<div class='row'>" +
							"<div class='col-xl-6'>" + 
								"<label>ItemID</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.ItemID + "'/>" +
							"</div>" +
							"<div class='col-xl-6'>" + 
								"<label>StartTime</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.ListingDetails.StartTime + "'/>" +
							"</div>" +
						"</div>" +
						"<div class='row'>" +
							"<div class='col-xl-6'>" + 
								"<label>StartPrice</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.StartPrice + "'/>" +
							"</div>" +
							"<div class='col-xl-6'>" + 
								"<label>CurrentPrice</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.SellingStatus.CurrentPrice + "'/>" +
							"</div>" +
						"</div>" +
						"<div class='row'>" +
							"<div class='col-xl-6'>" + 
								"<label>ListingType</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.ListingType + "'/>" +
							"</div>" +
							"<div class='col-xl-6'>" + 
								"<label>ListingDuration</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.ListingDuration + "'/>" +
							"</div>" +
						"</div>" +
						"<div class='row'>" +
							"<div class='col-xl-6'>" + 
								"<label>BidCount</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.SellingStatus.BidCount + "'/>" +
							"</div>" +
							"<div class='col-xl-6'>" + 
								"<label>HighBidder</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.SellingStatus.HighBidder.UserID + "'/>" +
							"</div>" +
						"</div>" +
					"</div>" + 
				"</div>";

	return template;
		
}