/**
 * Plugin Template frontend js.
 *
 *  @package Ebay Integration/JS
 */

jQuery( document ).ready(
	function ( e ) {

	}
);



// /////////////////// //
//   Button Handlers   //
// /////////////////// //

jQuery( document ).on("click", ".ebayintegration-btn", function(e){

	e.preventDefault();

	let element = $(this);

	// /////////////////// //
	//  eBay API buttons   //
	// /////////////////// //

	if( jQuery(this).data("action") == "getItems" ){

		getItemsRoutine();
			
	} 

	else if( jQuery(this).data("action") == "refreshToken" ){

		var token = refreshAccessToken();

		$.when(token).done(function(response){

			if( response["token_type"] == "User Access Token" ){
				alert("New Access Token Generated");
			} else {
				alert("Failed Getting Access Token");
			}
	
		});

	}

	// /////////////////// //
	//  User SKUs Button   //
	// /////////////////// //

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

		jQuery.ajax({
			method: 'get',
			url: "/wp-json/ebayintegration/v1/ajax",
			data: {
				action : action,
				user_id : user_id,
				sku : sku,
			},
			success: function(resp){

				var skus = "<ul>"
				$.each(resp.skus, function(k, v){
					skus = skus + "<li><a href='#' class='ebayintegration-btn' data-action='removeSKU' data-sku='" + v + "' data-user_id='" + user_id + "'> X </a> " + v + "</li>"
				});
				skus = skus + "</ul>"

				
				jQuery(document).find("#members_skus_table tbody tr.user_row[data-user_id='" + user_id + "'] td.skus").html(
					skus
				)

				jQuery(document).find(".ebay-item[data-sku='" + sku + "']").remove();				

				jQuery(document).find(".add_sku").modal("hide");

			},
			error: function(){
				// console.log("Error in AJAX");
			}
		});


	}	

	else if( jQuery(this).data("action") == "removeSKU" ){

		console.log("Removing SKU: " + jQuery(this).data("sku") + " from user_id: " + jQuery(this).data("user_id"));

		var user_id = jQuery(this).data("user_id");
		var sku = jQuery(this).data("sku");

		jQuery.ajax({
			method: 'get',
			url: "/wp-json/ebayintegration/v1/ajax",
			data: {
				action : jQuery(this).data("action"),
				user_id : user_id,
				sku : sku,
			},
			success: function(resp){	

				// console.log(resp);

				var skus = "<ul>"
				$.each(resp.skus, function(k, v){				
					skus = skus + "<li><a href='#' class='ebayintegration-btn' data-action='removeSKU' data-sku='" + v + "' data-user_id='" + user_id + "'> X </a> " + v + "</li>"
				});
				skus = skus + "</ul>"


				jQuery(document).find("#members_skus_table tbody tr.user_row[data-user_id='" + user_id + "'] td.skus").html(
					skus
				)

				console.log(jQuery(document).find("#members_skus_table tbody tr.user_row[data-user_id='" + user_id + "'] td.skus"));

			},
			error: function(){
				// console.log("Error in AJAX");
			}
		});


	}

	else if( jQuery(this).data("action") == "set_sku_user" ){


		jQuery(document).find(".add_sku").appendTo('body').modal("show");

		var sku = $(this).data("sku");

		var selected_items = "";
		
		$(document).find(".clicked_sku").val( sku );

		$.each( $(document).find(".ebay-item[data-sku='" + $(this).data("sku") + "']"), function( k, v ){
			selected_items = selected_items + "<tr><td>" + $(v).find("td").eq(1).html() + "</td><td>" + $(v).find("td").eq(5).html() + "</td></tr>";
		} );

		jQuery(document).find(".add_sku").find("#items_with_sku").html(
			"<table class='table table-sm table-border table-striped'>" +
			selected_items +
			"</table>"
		);

		jQuery(document).find(".add_sku").find("#items_with_sku").css("height", "300px");

	}
	else if( jQuery(this).data("action") == "show_log_consign_modal" ){

		jQuery(document).find(".log_consign_modal").appendTo('body').modal("show");

	}

	// ////////////////////////// //
	//  Add Consignment Buttons   //
	// ////////////////////////// //

	else if( jQuery(this).data("action") == "show_ship_batch_modal" ){

		jQuery(document).find(".ship_batch_modal").appendTo('body').modal("show");

	}

	else if( jQuery(this).data("action") == "confirmConsignCardsShipping" ){

		var package = confirmConsignCardsShipping();

		return "X";

	}

	else if( jQuery(this).data("action") == "confirmAddConsign" ){

		var card = confirmAddConsign();

		$(document).find(".log_consign_modal").find(".formbox").addClass("d-none");
		$(document).find(".log_consign_modal").find(".loading").removeClass("d-none");
		element.prop("disabled", "disabled");

		$.when(card).done( function( card ){

			$(document).find("#new_consignment tbody .empty_consignment").remove();
			$(document).find("#new_consignment_mobile tbody .empty_consignment").remove();

			$.each( card, function(k, v){

				$(document).find("#new_consignment tbody").prepend(
					"<tr class='consigned_item_row' data-id='" + v.id + "'>" +
						"<td>" +
							"<a class='text-dark consigned_item_view' data-id='" + v.id + "' href='#'>" +
								"<i class='fa-solid fa-xl fa-bars'></i>" + 
							"</a>" +
						"</td>" +
						"<td>" + v.year + "</td>" +
						"<td>" + v.brand + "</td>" +
						"<td>" + v.player_name + "</td>" +
						"<td class='text-end'>" + v.card_number + "</td>" +
						"<td class='text-end'>" + v.attribute_sn + "</td>" +
					"</tr>"
				);

				$(document).find("#new_consignment_mobile tbody").prepend(
					"<tr class='consigned_item_row' data-id='" + v.id + "'>" +
						"<td>" +
							"<div class='w-100 p-0 text-end' style='position: relative;'>" +
								"<a class='text-dark consigned_item_view' data-id='" + v.id + "' href='#' style='position: absolute; right: 0px;'>" +
									"<i class='fa-solid fa-xl fa-ellipsis'></i>" + 
								"</a>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-3'>Player</div>" +
								"<div class='col-9'>" +
								 	v.player_name +									
								"</div>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-3'>Year</div>" +
								"<div class='col-9'>" +
								 	v.year +									
								"</div>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-3'>Brand</div>" +
								"<div class='col-9'>" +
								 	v.brand +									
								"</div>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-3'>Card #</div>" +
								"<div class='col-9'>" +
								 	v.card_number +									
								"</div>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-3'>Attribute SN</div>" +
								"<div class='col-9'>" +
								 	v.attribute_sn +									
								"</div>" +
							"</div>" +
						"</td>" +
					"</tr>"
				);

			} );


			$(document).find(".log_consign_modal").find(".formbox").removeClass("d-none");
			$(document).find(".log_consign_modal").find(".loading").addClass("d-none");

			element.prop("disabled", "");
	
		});
	
	}



	else {

		console.log("Action Not Set: " + jQuery(this).data("action") );

	}
	
});

function confirmConsignCardsShipping(){

	var defObject = $.Deferred();  // create a deferred object.

	var user_id = parseInt( $(document).find(".ship_batch_modal").find(".formbox").find("[name='user_id']").val() );
	var carrier = $(document).find(".ship_batch_modal").find(".formbox").find("[name='carrier']").val();
	var shipped_by = $(document).find(".ship_batch_modal").find(".formbox").find("[name='shipped_by']").val();
	var tracking_number = $(document).find(".ship_batch_modal").find(".formbox").find("[name='tracking_number']").val();
	var shipping_date = $(document).find(".ship_batch_modal").find(".formbox").find("[name='shipping_date']").val();

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "confirmConsignCardsShipping",
			user_id: user_id,
			carrier: carrier,
			shipped_by: shipped_by,
			tracking_number: tracking_number,
			shipping_date: shipping_date,
		},
		success: function(resp){		
			defObject.resolve(resp);    //resolve promise and pass the response.
		},
		error: function(){
			console.log("Error in AJAX");
		}
	});

		
	return defObject.promise();
	
}


function confirmAddConsign(){

	var defObject = $.Deferred();  // create a deferred object.

	var user_id = parseInt( $(document).find(".log_consign_modal").find(".formbox").find("[name='user_id']").val() );
	var qty = parseInt( $(document).find(".log_consign_modal").find(".formbox").find("[name='qty']").val() );
	var year = $(document).find(".log_consign_modal").find(".formbox").find("[name='year']").val();
	var brand = $(document).find(".log_consign_modal").find(".formbox").find("[name='brand']").val();
	var player_name = $(document).find(".log_consign_modal").find(".formbox").find("[name='player_name']").val();
	var card_number = $(document).find(".log_consign_modal").find(".formbox").find("[name='card_number']").val();
	var attribute_sn = $(document).find(".log_consign_modal").find(".formbox").find("[name='attribute_sn']").val();

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "confirmAddConsign",
			user_id: user_id,
			qty: qty,
			year: year,
			brand: brand,
			player_name: player_name,
			card_number: card_number,
			attribute_sn: attribute_sn
		},
		success: function(resp){		
			defObject.resolve(resp);    //resolve promise and pass the response.
		},
		error: function(){
			console.log("Error in AJAX");
		}
	});

		
	return defObject.promise();
	
}



// ////////////////////// //
//   eBay API functions   //
// ////////////////////// //

function refreshAccessToken(){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'get',
		url: "/wp-json/ebayintegration/v1/ajax",
		data: { 
			action: "refreshToken"
		},
		success: function(resp){

			defObject.resolve(resp);    //resolve promise and pass the response.

		},
		error: function(){
			console.log("Error in AJAX");
		}
	});

	return defObject.promise();

}

function getItemPages(){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'get',
		url: "/wp-json/ebayintegration/v1/ajax",
		data: { 
			action: "getItemPages"
		},
		success: function(resp){

			defObject.resolve(resp);    //resolve promise and pass the response.

		},
		error: function(){
			console.log("Error in AJAX");
		}
	});

	return defObject.promise();

}

function getItemsRoutine(){

	var token = refreshAccessToken();
	var items = [];

	jQuery(document).find(".ebayintegration-items_box").find("#skus_table tbody").html(
		'<tr>' +
			'<td colspan="6" class="my-5 text-center">Getting Items From eBay</td>' +
		'</tr>'
	);

	$.when(token).done(function(response){

		console.log("Refreshed Access Token");

		if( response["token_type"] == "User Access Token" ){

			console.log("Get Item Pages - GO");

			var item_pages = getItemPages();

			$.when(item_pages).done( function(pages){

				var ListingDuration = [];
				var ListingType = [];
				var SKU = [];

				$.each( items, function( k, v ){

					if(jQuery.inArray( v.ListingDuration, ListingDuration) == -1)	{
						ListingDuration.push(v.ListingDuration);
					}					

					if(jQuery.inArray( v.ListingType, ListingType) == -1)	{
						ListingType.push(v.ListingType);
					}					

					if(jQuery.inArray( v.SKU, SKU) == -1){
						SKU.push(v.SKU);
					}					

				});

				jQuery(document).find(".ebayintegration-items_box").find("#skus_table tbody").empty();

				jQuery(document).find(".ebayintegration-items_box").find("#skus_table tbody").append( itemtemplate(pages.undefined_items) );
				

				$.each( pages.ebay_items, function( k, v ){
					console.log( v.ItemID );
				});


			});

		}

	});

}

function itemtemplate(data){

	var template = '';


	$.each(data,function(k, v ){
		template = template + "<tr class='ebayintegration-btn ebay-item' data-action='set_sku_user' data-item_id='" + v.ItemID + "' data-sku='" + v.SKU + "'>"; 
		template = template + "<td>" + v.ItemID + "</td>";
		template = template + "<td>" + v.Title + "</td>";
		template = template + "<td>" + v.SKU + "</td>";
		template = template + "<td>" + v.ListingType + "</td>";
		template = template + "<td>" + v.ListingDuration + "</td>";
		template = template + "<td>" + v.SellingStatus.CurrentPrice + "</td>";
		template = template + "</tr>";
	});

	return template;

}

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

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'get',
		url: "/wp-json/ebayintegration/v1/ajax",
		data: { 
			action: "getItems",
			page_number: page
		},
		success: function(resp){

			defObject.resolve(resp);    //resolve promise and pass the response.

		},
		error: function(){
			console.log("Error in AJAX");
		}
	});

	return defObject.promise();

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

$(document).find(".search_box").on("keyup", function() {
	console.log($(this).val().toLowerCase());
    var value = $(this).val().toLowerCase();
    $(document).find($(this).data("target") + " tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});


$(document).on("change", ".user_list_select", function(){
	console.log( $(this) );
});

$(document).on("change", "#mobile_tab_select", function(){
	console.log($(this).val());

    window.location.href = $(this).val();
});


