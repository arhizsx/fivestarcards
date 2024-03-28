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
					var current = 0;

					for(var i=1; i <= loops; i++){						
						if(getItems(i)){
							current = current+1;
							console.log("CURRENT: " + current);
						}
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
	else if( jQuery(this).data("action") == "addSKU" ){

		jQuery(document).find(".add_sku").find("[name='action']").attr( "value", "confirmAddSKU" );
		jQuery(document).find(".add_sku").find("[name='user_id']").attr( "value", jQuery(this).data("user_id") );
		jQuery(document).find(".add_sku").find("[name='user_name']").attr( "value", jQuery(this).data("user_name") );
		jQuery(document).find(".add_sku").find("[name='user_email']").attr( "value", jQuery(this).data("user_email") );
		jQuery(document).find(".add_sku").find("[name='id']").attr( "value", parseInt( jQuery(this).data("user_id")) + 1000 );

		jQuery(document).find(".add_sku").appendTo('body').modal("show");

	}
	else if( jQuery(this).data("action") == "confirmAddSKU" ){
		
		var action = jQuery(document).find(".add_sku").find("#add_sku_form").find("[name='action']").val();
		var user_id = jQuery(document).find(".add_sku").find("#add_sku_form").find("[name='user_id']").val();
		var sku = jQuery(document).find(".add_sku").find("#add_sku_form").find("[name='sku']").val();

		// console.log(action);

		jQuery.ajax({
			method: 'get',
			url: "/wp-json/ebayintegration/v1/ajax",
			data: {
				action : action,
				user_id : user_id,
				sku : sku,
			},
			success: function(resp){

				$skus = "<ul>"
				$.each(resp.skus, function(k, v){
					$skus = $skus + "<li>" + v + "</li>"
				});
				$skus = $skus + "</ul>"

				console.log( jQuery(document).find("#members_skus_table").find(".user_row [data-user_id='" + user_id + "'").html() );

				jQuery(document).find(".user_row [data-user_id='" + user_id + "'").find(".skus").html( $skus );

				// console.log( jQuery(document).find(".user_row [data-user_id='" + user_id + "'") );


				jQuery(document).find(".user_row [data-user_id='" + user_id + "'").find(".ebay").html( resp.ebay.length );

				// console.log(resp);

			},
			error: function(){
				// console.log("Error in AJAX");
			}
		});


	}

	

});

function eBayItemTemplate(data){

	var template = "";

	var title = data.Title.replace(/'/g,"&apos;").replace(/"/g,"&quot;")

	template = "<div class='row mt-3 pt-3 border-top' data-item_id=''>" +
					"<div class='col-lg-3 col-xl-3'>" + 
						"<a class='item_href' data-item_id='" + data.ItemID +  "' href='' target='_blank'>" +
							"<img src='' class='item_img w-100' data-item_id='" + data.ItemID +  "' />" +
						"</a>" +
					"</div>" + 
					"<div class='col-lg-9 col-xl-9'>"+
						"<div class='row'>" +
							"<div class='col-xl-12'>" + 
								"<label>Title</label>" +
								"<input type='text' class='form-control mb-3' value='" + title + "'/>" +
							"</div>" +
						"</div>" + 
						"<div class='row'>" +
							"<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6'>" + 
								"<label>ItemID</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.ItemID + "'/>" +
							"</div>" +
							"<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6'>" + 
								"<label>StartTime</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.ListingDetails.StartTime + "'/>" +
							"</div>" +
						"</div>" +
						"<div class='row'>" +
							"<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6'>" + 
								"<label>ListingType</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.ListingType + "'/>" +
							"</div>" +
							"<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6'>" + 
								"<label>ListingDuration</label>" +
								"<input type='text' class='form-control mb-3' value='" + data.ListingDuration + "'/>" +
							"</div>" +
						"</div>";

	if(data.ListingDuration == "GTC"){
		template = template + 	"<div class='row'>" +
									"<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6'>" + 
										"<label>BuyItNowPrice</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.BuyItNowPrice + "'/>" +
									"</div>" +
									"<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6'>" + 
										"<label>CurrentPrice</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.SellingStatus.CurrentPrice + "'/>" +
									"</div>" +
								"</div>";

	} else {
		template = template + 	"<div class='row'>" +
									"<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6'>" + 
										"<label>StartPrice</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.StartPrice + "'/>" +
									"</div>" +
									"<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6'>" + 
										"<label>CurrentPrice</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.SellingStatus.CurrentPrice + "'/>" +
									"</div>" +
								"</div>";
	}				

	template = template + 		"<div class='row'>" +
									"<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6'>" + 
										"<label>SKU</label>" +
										"<input type='text' class='form-control mb-3' value='" + data.SKU + "'/>" +
									"</div>" +
									"<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6'>" + 
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

				console.log("Items on " + page + " processed");
				return true;

			} else {

				if(resp.data == "Refresh Access Token"){
					console.log("Do Refresh Access Token");
				} else {
					console.log(resp.data);
				}

				return false;

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
				var href = (resp.data.Item.ListingDetails.ViewItemURL);
				// console.log(href);
				
				// $(document).find(".item_img[data-item_id='" + item_id + "']").attr("src", img);
				// $(document).find(".item_href[data-item_id='" + item_id + "']").attr("href", href);

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