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


				if(resp.error != true){	

					var loops = parseInt(resp["data"]);				
					for(var i=1; i <= loops; i++){
						getItems(i);
					}

				} else {

					if(resp.data == "Refresh Access Token"){
						console.log("Do Refresh Access Token");
					} else {
						console.log(resp.data);
					}

				}

			},
			error: function(){
				console.log("Error in AJAX");
			}
		});

	
	} 
	else if( jQuery(this).data("action") == "refreshToken" ){

		jQuery.ajax({
			method: 'get',
			url: "/wp-json/ebayintegration/v1/ajax",
			data: { 
				action: "refreshToken"
			},
			success: function(resp){


				if(resp.error != true){	

					alert("Reconnected to eBay");

				} else {

					if(resp.data == "Refresh Access Token"){
						console.log("Do Refresh Access Token");
					} else {
						console.log(resp.data);
					}

				}

			},
			error: function(){
				console.log("Error in AJAX");
			}
		});

	}


});

function eBayItemTemplate(data){

	var template = "";

	template = "<div class='row mt-3 pt-3 border-top' data-item_id=''>" +
					"<div class='col-lg-3 col-xl-3'>" + 
						"<img src='' class='item_img' data-item_id='" + data.ItemID +  "' />" +
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
								"<label>ListingType</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.ListingType + "'/>" +
							"</div>" +
							"<div class='col-xl-6'>" + 
								"<label>ListingDuration</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.ListingDuration + "'/>" +
							"</div>" +
						"</div>";

	if(data.ListingDuration == "GTC"){
		template = template + 	"<div class='row'>" +
									"<div class='col-xl-6'>" + 
										"<label>BuyItNowPrice</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.BuyItNowPrice + "'/>" +
									"</div>" +
									"<div class='col-xl-6'>" + 
										"<label>CurrentPrice</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.SellingStatus.CurrentPrice + "'/>" +
									"</div>" +
								"</div>";

	} else {
		template = template + 	"<div class='row'>" +
									"<div class='col-xl-6'>" + 
										"<label>StartPrice</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.StartPrice + "'/>" +
									"</div>" +
									"<div class='col-xl-6'>" + 
										"<label>CurrentPrice</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.SellingStatus.CurrentPrice + "'/>" +
									"</div>" +
								"</div>";
	}				

	template = template + 		"<div class='row'>" +
									"<div class='col-xl-6'>" + 
										"<label>SKU</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.SKU + "'/>" +
									"</div>" +
									"<div class='col-xl-6'>" + 
										"<label>QuantityAvailable</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.QuantityAvailable + "'/>" +
									"</div>" +
								"</div>";

							"</div>" +
						"</div>" + 
					"</div>";

	return template;
		
}

function getItems(page){

	jQuery.ajax({
		method: 'get',
		url: "/wp-json/ebayintegration/v1/ajax",
		data: { 
			action: "getItems",
			page_number: page
		},
		success: function(resp){

			if(resp.error != true){
				jQuery.each(resp.data.ActiveList.ItemArray.Item, function(k, v){		
					jQuery(document).find(".ebayintegration-items_box").append(eBayItemTemplate(v));
					getItemInfo(v.ItemID);
				});	
			} else {

				if(resp.data == "Refresh Access Token"){
					console.log("Do Refresh Access Token");
				} else {
					console.log(resp.data);
				}

			}

		},
		error: function(){
			console.log("Error in AJAX");
		}
	});

}

function getItemInfo(item_id){

	jQuery.ajax({
		method: 'get',
		url: "/wp-json/ebayintegration/v1/ajax",
		data: { 
			action: "getItemInfo",
			item_id: item_id
		},
		success: function(resp){

			if(resp.error != true){

				var img = (resp.data.Item.PictureDetails.PictureURL[0]);
				$(document).find(".item_img[data-item_id='" + item_id + "']").attr("src", img);


			} else {

				if(resp.data == "Refresh Access Token"){
					console.log("Do Refresh Access Token");
				} else {
					console.log(resp.data);
				}

			}

		},
		error: function(){
			console.log("Error in AJAX");
		}
	});


}