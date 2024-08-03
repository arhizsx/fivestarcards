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
	//  Add Payout Buttons   //
	// ////////////////////////// //

	else if( jQuery(this).data("action") == "add_new_payout" ){

		jQuery(document).find(".add_new_payment_modal").appendTo('body').modal("show");

	}
	else if( jQuery(this).data("action") == "confirmPayoutRequest" ){

		var payout = confirmPayoutRequest();

		$.when( payout ).done( function( payout ){	
			// window.location = "/my-account/payout/?payout_id=" + payout.payout_id ;	
		});
	}
	else if( jQuery(this).data("action") == "show_payment_request" ){

		jQuery(document).find(".show_payment_request_modal").appendTo('body').modal("show");

		var payout = getPayoutRequest( $(this).data("payout_id") );

		$.when( payout ).done( function( payout ){	

			$(document).find("#payout_cards_table tbody").empty();
			var payout_total = 0;

			$.each( payout.cards, function( k, v){

				var card = JSON.parse(v.data, true);
				var sold_price = parseFloat(card.TransactionPrice).toFixed(2);
				var rate = 0;
				var fees = 0;
				var final = 0;

				if( sold_price < 10 ){
					rate = 1;
					fees = 3;
					final = sold_price * rate + fees;
				}
				else if( sold_price >= 10 &&  sold_price <= 49.99  ){
					rate = 0.82;
					fees = 0;
					final = sold_price * rate + fees;
				}
				else if( sold_price >= 50 &&  sold_price <= 99.99  ){
					rate = 0.84;
					fees = 0;
					final = sold_price * rate + fees;
				}
				else if( sold_price >= 100 &&  sold_price <= 199.99  ){
					rate = 0.85;
					fees = 0;
					final = sold_price * rate + fees;
				}
				else if( sold_price >= 200 &&  sold_price <= 499.99  ){
					rate = 0.86;
					fees = 0;
					final = sold_price * rate + fees;
				}
				else if( sold_price >= 500 &&  sold_price <= 999.99  ){
					rate = 0.87;
					fees = 0;
					final = sold_price * rate + fees;
				}
				else if( sold_price >= 1000 &&  sold_price <= 2999.99  ){
					rate = 0.88;
					fees = 0;
					final = sold_price * rate + fees;
				}
				else if( sold_price >= 3000 &&  sold_price <= 4999.99  ){
					rate = 0.90;
					fees = 0;
					final = sold_price * rate + fees;
				}
				else if( sold_price >= 5000 &&  sold_price <= 8999.99  ){
					rate = 0.92;
					fees = 0;
					final = sold_price * rate + fees;
				}
				else if( sold_price >= 9000  ){
					rate = 0.94;
					fees = 0;
					final = sold_price * rate + fees;
				}

				rate = parseFloat(rate).toFixed(2) * 100;				
				fees = parseFloat(fees).toFixed(2);				
				final = parseFloat(final).toFixed(2);		
				
				payout_total = parseFloat(payout_total) + parseFloat(final);

				$(document).find("#payout_cards_table tbody").append(
					"<tr>" +	
						"<td class='text-start'><a href='" + card.Item.ListingDetails.ViewItemURL + "' target='_blank'>" + card.Item.Title + "</a></td>" +
						"<td class='text-end'>$" + sold_price + "</td>" +
						"<td class='text-end'>" + rate.toFixed(2) + "%</td>" +
						"<td class='text-end'>$" + fees + "</td>" +
						"<td class='text-end'>$" + final + "</td>" +
					"</tr>"
				);
			});

			var data = JSON.parse(payout.payout[0].data, true);
			$(document).find(".show_payment_request_modal").find("[name='remarks']").val(data.remarks);
			$(document).find(".show_payment_request_modal").find("[name='total_amount']").val(payout_total);
			$(document).find(".show_payment_request_modal").find("[name='cards_count']").val(payout.cards.length);

		});
		

	}

	

	// ////////////////////////// //
	//  Add Order Buttons   //
	// ////////////////////////// //

	else if( jQuery(this).data("action") == "add_new_order" ){

		jQuery(document).find(".add_new_order_modal").appendTo('body').modal("show");

	}

	// ////////////////////////// //
	//  Add Grading Buttons   //
	// ////////////////////////// //


	else if( jQuery(this).data("action") == "grading_picture_box_click" ){

		jQuery(document).find(".picture_box_modal").appendTo('body').modal("show");
		
		jQuery(document).find(".picture_box_modal").find("[name='card_id']").val( $(this).data("id") );
		jQuery(document).find(".picture_box_modal").find("[name='user_id']").val( $(this).data("user_id") );
	}

	else if( jQuery(this).data("action") == "show_log_grading_modal" ){

		jQuery(document).find(".log_grading_modal").appendTo('body').modal("show");

	}

	else if( jQuery(this).data("action") == "show_import_grading_modal" ){

		jQuery(document).find(".import_grading_modal").appendTo('body').modal("show");

	}

	else if( jQuery(this).data("action") == "grading_table_clear_List" ){

		jQuery(document).find(".clear_grading_modal").appendTo('body').modal("show");

	}

	else if( jQuery(this).data("action") == "grading_table_checkout" ){

		jQuery(document).find(".checkout_grading_modal").appendTo('body').modal("show");

	}
	
	else if( jQuery(this).data("action") == "confirmAddGrading" ){
		
		var card = confirmAddGrading();

		$(document).find(".log_grading_modal").find(".formbox").addClass("d-none");
		$(document).find(".log_grading_modal").find(".loading").removeClass("d-none");
		element.prop("disabled", "disabled");

		$.when(card).done( function( card ){

			$(document).find("#new_grading tbody .empty_grading").remove();
			$(document).find("#new_grading_mobile tbody .empty_grading").remove();

			$.each( card, function(k, v){

				$(document).find("#new_grading tbody").prepend(
					"<tr class='consigned_item_row' data-id='" + v.id + "'>" +
						"<td>" +
							"<a class='text-danger   ebayintegration-btn' data-action='removeGradingCardRow' data-id='" + v.id + "' href='#'>" +
								"<i class='fa-solid fa-lg fa-xmark'></i>" + 
							"</a>" +
						"</td>" +
						"<td style='width: 100px; padding: 0px;'>" +
							"<div class='d-flex justify-content-center align-items-center picture_box ebayintegration-btn' data-action='grading_picture_box_click'  data-id='" + v.id + "' data-user_id='" + v.user_id + "'>" +
								"<i class='fa-solid fa-file-image fa-2x'></i>" +
							"</div>" +
						"</td>" +
						"<td>" + v.player + "</td>" +
						"<td>" + v.year + "</td>" +
						"<td>" + v.brand + "</td>" +
						"<td>" + v.card_number + "<br><small>" + v.attribute_sn + "</small></td>" +
						"<td class='text-end'>$" + v.dv + "</td>" +
						"<td class='text-end'>$" + v.per_card + "</td>" +
					"</tr>"
				);

				$(document).find("#new_grading_mobile tbody").prepend(
					"<tr class='consigned_item_row' data-id='" + v.id + "'>" +
						"<td colspan='2'>" +
							"<div class='w-100 p-0 text-end' style='position: relative;'>" +
								"<a class='text-danger  ebayintegration-btn' data-action='removeGradingCardRow' data-id='" + v.id + "' href='#' style='position: absolute; right: 0px;'>" +
									"<i class='fa-solid fa-xl fa-xmark'></i>" + 
								"</a>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-4'>Player</div>" +
								"<div class='col-8'>" +
								 	v.player +									
								"</div>" + 
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-4'>Year</div>" +
								"<div class='col-8'>" +
								 	v.year +									
								"</div>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-4'>Brand</div>" +
								"<div class='col-8'>" +
								 	v.brand +									
								"</div>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-4'>Card #</div>" +
								"<div class='col-8'>" +
								 	v.card_number +									
								"</div>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-4'>Attribute SN</div>" +
								"<div class='col-8'>" +
								 	v.attribute_sn +									
								"</div>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-4'>Declared Value</div>" +
								"<div class='col-8'>$0.00" +
								 	
								"</div>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-4'>Grading</div>" +
								"<div class='col-8'>$0.00" +
								"</div>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-sm-4'>Photo</div>" +
								"<div class='col-sm-8'>" +
									"<div class='d-flex justify-content-center align-items-center picture_box'>" +
										"<i class='fa-solid fa-file-image fa-2x'></i>" +
									"</div>" +
								"</div>" +
							"</div>" +
						"</td>" +
					"</tr>"
				);

			} );


			$(document).find(".log_grading_modal").find(".formbox").removeClass("d-none");
			$(document).find(".log_grading_modal").find(".loading").addClass("d-none");

			element.prop("disabled", "");
	
		});
	
	}

	else if( jQuery(this).data("action") == "removeGradingCardRow" ){

		var id = element.data("id");
		var user_id = element.data("user_id");
	
		var card = removeGradingCardRow(id, user_id);
		
		element.html('<i class="fa-solid fa-md fa-spinner fa-spin"></i>');

		$.when( card ).done( function( card ){

			// console.log( $(document).find(".consigned_item_row[data-id='" + element.data("id") + "']").html() );
			if( $(document).find(".consigned_item_row[data-id='" + id + "']").closest("tbody").find(".consigned_item_row").length  == 2 ){

				$(document).find("#new_grading tbody").append(
					'<tr class="empty_grading">' +
						'<td colspan="8" class="text-center py-5">' +
							'Empty' +
						'</td>' +
					'</tr>'
				);
				
				$(document).find("#new_grading_mobile tbody").append(
					'<tr class="empty_grading">' +
						'<td colspan="2" class="text-center py-5">' +
							'Empty' +
						'</td>' +
					'</tr>'
				);
			}

			$(document).find(".consigned_item_row[data-id='" + id + "']").remove();

			// element.html('<i class="fa-solid fa-xl fa-xmark"></i>');

		});

	}

	else if( jQuery(this).data("action") == "show_grading_table_clear_list" ){

		jQuery(document).find(".clear_grading_modal").appendTo('body').modal("show");

	}


	else if( jQuery(this).data("action") == "show_grading_table_checkout" ){

		jQuery(document).find(".checkout_grading_modal").appendTo('body').modal("show");

	}


	else if( jQuery(this).data("action") == "confirmGradingTableClearList" ){

		var grading_type = element.data("grading_type");
		var user_id = element.data("user_id");
	
		var card = confirmGradingTableClearList(grading_type, user_id);
		
		element.html('<i class="fa-solid fa-md fa-spinner fa-spin"></i>');

		$.when( card ).done( function( card ){
			location.reload();
		});
	}

	else if( jQuery(this).data("action") == "confirmGradingTableCheckout" ){

		var grading_type = element.data("grading_type");
		var user_id = element.data("user_id");
	
		var card = confirmGradingTableCheckout(grading_type, user_id);
		
		element.html('<i class="fa-solid fa-md fa-spinner fa-spin"></i>');

		$.when( card ).done( function( card ){

			window.location.href = "/my-account/grading/view-order/?mode=open&id=" + card.checkout_post_id;
			console.log(card);
		});

	}
	
	else if( jQuery(this).data("action") == "confirmPhotoAdd" ){

		console.log( $(this).data() );

        e.preventDefault();

        if( Checker() ) {

            let form = new FormData( $("#photo_upload_form")[0] );

            $.ajax({
                type: 'post',
				url: "/wp-json/ebayintegration/v1/post",
                data: form,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                success: function(resp){

					console.log(resp.data.file.baseurl);
					console.log(resp.params.card_id);

					$(document).find(".ebayintegration-btn[data-action='grading_picture_box_click'][data-id='" + resp.params.card_id + "']").html(
						"<img src='" + resp.data.file.baseurl + "'>"
					);

                },
                error: function(){
                    console.log("Error in AJAX");
                }
            });


        }

	}

	
	
	// ////////////////////////// //
	//  Add Consignment Buttons   //
	// ////////////////////////// //

	else if( jQuery(this).data("action") == "show_ship_batch_modal" ){

		jQuery(document).find(".ship_batch_modal").appendTo('body').modal("show");

	}

	else if( jQuery(this).data("action") == "confirmConsignCardsShipping" ){

		var package = confirmConsignCardsShipping();

		$(document).find(".ship_batch_modal").find(".formbox").addClass("d-none");
		$(document).find(".ship_batch_modal").find(".loading").removeClass("d-none");
		element.prop("disabled", "disabled");

		$.when( package ).done( function( package ){

	
			$(document).find(".ship_batch_modal").find(".formbox").removeClass("d-none");
			$(document).find(".ship_batch_modal").find(".loading").addClass("d-none");
			element.prop("disabled", "");

			location.href = "/my-account/consignment/?mode=orders";
	
		});

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
							"<a class='text-danger   ebayintegration-btn' data-action='removeConsignedCardRow' data-id='" + v.id + "' href='#'>" +
								"<i class='fa-solid fa-lg fa-xmark'></i>" + 
							"</a>" +
						"</td>" +
						"<td>" + v.year + "</td>" +
						"<td>" + v.brand + "</td>" +
						"<td>" + v.player + "</td>" +
						"<td class='text-end'>" + v.card_number + "</td>" +
						"<td class='text-end'>" + v.attribute_sn + "</td>" +
					"</tr>"
				);

				$(document).find("#new_consignment_mobile tbody").prepend(
					"<tr class='consigned_item_row' data-id='" + v.id + "'>" +
						"<td>" +
							"<div class='w-100 p-0 text-end' style='position: relative;'>" +
								"<a class='text-danger  ebayintegration-btn' data-action='removeConsignedCardRow' data-id='" + v.id + "' href='#' style='position: absolute; right: 0px;'>" +
									"<i class='fa-solid fa-xl fa-xmark'></i>" + 
								"</a>" +
							"</div>" +
							"<div class='row'>" +
								"<div class='small text-secondary col-3'>Player</div>" +
								"<div class='col-9'>" +
								 	v.player +									
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

	else if( jQuery(this).data("action") == "removeConsignedCardRow" ){

		var id = element.data("id");
		var user_id = element.data("user_id");
	
		var card = removeConsignedCardRow(id, user_id);
		
		element.html('<i class="fa-solid fa-md fa-spinner fa-spin"></i>');

		$.when( card ).done( function( card ){

			// console.log( $(document).find(".consigned_item_row[data-id='" + element.data("id") + "']").html() );
			if( $(document).find(".consigned_item_row[data-id='" + id + "']").closest("tbody").find(".consigned_item_row").length  == 2 ){

				$(document).find("#new_consignment tbody").append(
					'<tr class="empty_consignment">' +
						'<td colspan="7" class="text-center py-5">' +
							'Empty' +
						'</td>' +
					'</tr>'
				);
				
				$(document).find("#new_consignment_mobile tbody").append(
					'<tr class="empty_consignment">' +
						'<td class="text-center py-5">' +
							'Empty' +
						'</td>' +
					'</tr>'
				);
			}

			$(document).find(".consigned_item_row[data-id='" + id + "']").remove();

			// element.html('<i class="fa-solid fa-xl fa-xmark"></i>');

		});

	}
	
	else if( jQuery(this).data("action") == "consignedCardNotReceived" ){
	
		var id = element.data("id");
		var user_id = element.data("user_id");

		var card = consignedCardNotReceived(id, user_id);

		
		$.when( card ).done( function( card ){

			if( card.error == false ){

				if( $(document).find(".consigned_item_row[data-id='" + id + "']").closest("tbody").find(".consigned_item_row").length  == 1 ){

					$(document).find("#receiving_consignment tbody").append(
						'<tr class="empty_consignment">' +
							'<td colspan="8" class="text-center py-5">' +
								'Empty' +
							'</td>' +
						'</tr>'
					);
					
				}

				$(document).find(".consigned_item_row[data-id='" + id + "']").remove();

			} else {
				alert("Error encountered");
			}



		});
	}

	else if( jQuery(this).data("action") == "confirmConsignedCardReceived" ){
	
		var id = element.data("id");
		var user_id = element.data("user_id");

		var card = confirmConsignedCardReceived(id, user_id);
		
		$.when( card ).done( function( card ){

			if( card.error == false ){
				if( $(document).find(".consigned_item_row[data-id='" + id + "']").closest("tbody").find(".consigned_item_row").length  == 1 ){

					$(document).find("#receiving_consignment tbody").append(
						'<tr class="empty_consignment">' +
							'<td colspan="8" class="text-center py-5">' +
								'Empty' +
							'</td>' +
						'</tr>'
					);
					
				}

				$(document).find(".consigned_item_row[data-id='" + id + "']").remove();
			} else {
				alert("Error encountered");
			}

		});

	}

	else if( jQuery(this).data("action") == "confirmConsignedCardReceivedAll" ){
	
		var id = element.data("id");
		var user_id = element.data("user_id");

		var order = confirmConsignedCardReceivedAll(id, user_id);
		
		$.when( order ).done( function( order ){

		});

	}	

	else if( jQuery(this).data("action") == "confirmUnvailableConsignedCard" ){
	
		var id = element.data("id");
		var user_id = element.data("user_id");

		var card = confirmUnvailableConsignedCard(id, user_id);
		
		$.when( card ).done( function( card ){
			
			if( card.error == false ){
				if( $(document).find(".consigned_item_row[data-id='" + id + "']").closest("tbody").find(".consigned_item_row").length  == 1 ){

					$(document).find("#receiving_consignment tbody").append(
						'<tr class="empty_consignment">' +
							'<td colspan="8" class="text-center py-5">' +
								'Empty' +
							'</td>' +
						'</tr>'
					);
					
				}

				$(document).find(".consigned_item_row[data-id='" + id + "']").remove();
			} else {
				alert("Error encountered");
			}

		});
	}

	else if( jQuery(this).data("action") == "postToEbayEditor" ){

		jQuery(document).find(".post_to_ebay_editor_modal").appendTo('body').modal("show");
		
	}

	else if( jQuery(this).data("action") == "showConsignedCardDetailsModal" ){

		var id = element.data("id");
		var user_id = element.data("user_id");

		var card = showConsignedCardDetailsModal(id, user_id);
		
		$.when( card ).done( function( card ){
			
			if( card.error == false ){

				var data = JSON.parse( card.card.data );

				$(document).find(".consigned_card_details_modal").find("[name='id']").val( id );
				$(document).find(".consigned_card_details_modal").find("[name='user_id']").val( user_id );
				$(document).find(".consigned_card_details_modal").find("[name='qty']").val( data.qty );
				$(document).find(".consigned_card_details_modal").find("[name='year']").val( data.year );
				$(document).find(".consigned_card_details_modal").find("[name='brand']").val( data.brand );
				$(document).find(".consigned_card_details_modal").find("[name='player']").val( data.player );
				$(document).find(".consigned_card_details_modal").find("[name='card_number']").val( data.card_number );
				$(document).find(".consigned_card_details_modal").find("[name='attribute_sn']").val( data.attribute_sn );
				$(document).find(".consigned_card_details_modal").find("[name='new_status']").val( card.card.status );				

				jQuery(document).find(".consigned_card_details_modal").appendTo('body').modal("show");

			} else {
				alert("Error encountered");
			}

		});



		
	}

	else if( jQuery(this).data("action") == "confirmUpdateConsignedCardDetails" ){
	
		var card = confirmUpdateConsignedCardDetails();
		
		$.when( card ).done( function( card ){
			
			if( card.error == false ){

				location.reload();

			} else {
				alert("Error encountered");
			}

		});
	}

	else if( jQuery(this).data("action") == "showMemberInfoModal" ){

		$(document).find("#main-header").css("z-index", 99998)
		$(document).find("#wpadminbar").css("z-index", 99998)		

		jQuery(document).find(".member_info_modal").prependTo('body').modal("show");

		console.log( element.data("user_id") );

		$(document).find(".member_info_modal").find(".member_view_menu").find("button").data("user_id", "");
		$(document).find(".member_info_modal").find(".member_view_menu").find("button").data("user_id", element.data("user_id"));

		$(document).find(".member_view_menu").find("button").removeClass("active");
		$(document).find(".member_view_menu").find("button[data-action='getViewMemberDetails']").addClass("active");

		$(document).find(".formbox").find(".boxes").addClass("d-none");
		$(document).find(".formbox").find(".member_details_box").removeClass("d-none");

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "getViewMemberDetails",
				user_id: element.data("user_id"),
			},
			success: function(resp){		

				display_name = resp.user[0].display_name;
				customer_number =  parseInt( resp.user[0].ID ) + 1000;
				user_email = resp.user[0].user_email;

				$(document).find(".member_details_box").find("[name='display_name']").val( display_name );
				$(document).find(".member_details_box").find("[name='customer_number']").val( customer_number );
				$(document).find(".member_details_box").find("[name='user_email']").val( user_email );

				$(document).find("[data-action='messageUser']").data("user_id", resp.user[0].ID );
				$(document).find("[data-action='loginToAccount']").data("user_id", resp.user[0].ID );
				$(document).find("[data-action='deactivateMember']").data("user_id", resp.user[0].ID );
				$(document).find("[data-action='saveMemberDetailsChanges']").data("user_id", resp.user[0].ID );

				$(document).find(".message_user_modal").find("[name='display_name']").val( display_name );
				$(document).find(".message_user_modal").find("[name='customer_number']").val( customer_number );
				$(document).find(".message_user_modal").find("[name='user_email']").val( user_email );

				console.log( resp );

			},
			error: function(){
				console.log("Error in AJAX");
			}
		});

	}

	else if( jQuery(this).data("action") == "getViewMemberDetails" ){

		$(document).find(".member_view_menu").find("button").removeClass("active");
		element.addClass("active");

		console.log( element );

		var user_id = element.data("user_id");

		$(document).find(".formbox").find(".boxes").addClass("d-none");
		$(document).find(".formbox").find(".member_details_box").removeClass("d-none");

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "getViewMemberDetails",
				user_id: user_id,
			},
			success: function(resp){		

				display_name = resp.user[0].display_name;
				customer_number =  parseInt( resp.user[0].ID ) + 1000;
				user_email = resp.user[0].user_email;

				$(document).find(".member_details_box").find("[name='display_name']").val( display_name );
				$(document).find(".member_details_box").find("[name='customer_number']").val( customer_number );
				$(document).find(".member_details_box").find("[name='user_email']").val( user_email );


				console.log( resp );

			},
			error: function(){
				console.log("Error in AJAX");
			}
		});
		
	}
	
	else if( jQuery(this).data("action") == "getViewMemberEbay" ){
		
		$(document).find(".member_view_menu").find("button").removeClass("active");
		element.addClass("active");

		var user_id = element.data("user_id");

		$(document).find(".formbox").find(".boxes").addClass("d-none");
		$(document).find(".formbox").find(".member_ebay_box").removeClass("d-none");

		$(document).find(".member_ebay_box").find("table tbody").empty();

		$(document).find(".member_ebay_box").find("table tbody").append(
			"<tr>" +
				"<td class='text-center p-5' colspan='2'>Empty</td>" + 
			"</tr>"
		)

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "getViewMemberEbay",
				user_id: user_id,
			},
			success: function(resp){		

				console.log(resp.card.length);

				$(document).find(".member_ebay_box").find("table tbody").empty();

				for( i=0; i<= resp.card.length; i++ ){

					var Title = "";
					var URL = "";
	
					var data =  JSON.parse( resp.card[i].data );
					if( 'Item' in data ) {
						Title = data.Item.Title;
						URL = data.Item.ListingDetails.ViewItemURL;
					} else {
						Title = data.Title;
						URL = data.ListingDetails.ViewItemURL;
					}

					$(document).find(".member_ebay_box").find("table tbody").append(
						"<tr>" +
							"<td>" + resp.card[i].status + "</td>" + 
							"<td><a target='_blank' href='" + URL + "'>" + Title + "</a></td>" + 
						"</tr>"
					)

				}

				// defObject.resolve(resp);    //resolve promise and pass the response.
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});

	}
	
	else if( jQuery(this).data("action") == "getViewMemberSKU" ){
		
		$(document).find(".member_view_menu").find("button").removeClass("active");
		element.addClass("active");
	
		var user_id = element.data("user_id");

		$(document).find(".formbox").find(".boxes").addClass("d-none");
		$(document).find(".formbox").find(".member_sku_box").removeClass("d-none");

		$(document).find(".member_sku_box").find("table tbody").empty();

		$(document).find(".member_sku_box").find("table tbody").append(
			"<tr>" +
				"<td class='text-center p-5' colspan='2'>Empty</td>" + 
			"</tr>"
		)


		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "getViewMemberSKU",
				user_id: user_id,
			},
			success: function(resp){	
				
				if( resp.sku.length > 0 ){
					
					$(document).find(".member_sku_box").find("table tbody").empty();

					$.each(resp.sku, function( k, v ){

						$(document).find(".member_sku_box").find("table tbody").append(
							"<tr class='sku_row' data-sku='" + v + "'>" +
								"<td>" + v + "</td>" + 
								"<td class='fit text-center'>" +
									"<a href='#' class='ebayintegration-btn  text-danger' data-action='removeMemberSKU' data-user_id='" + resp.user_id + "' data-sku='" + v + "'>" +
										"<i class='fa-solid fa-lg fa-xmark'></i>" +
									"</a>" +
								"</td>" + 
							"</tr>"
						)
	
					});

				}

				// defObject.resolve(resp);    //resolve promise and pass the response.
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});


	}

	else if( jQuery(this).data("action") == "getViewUnmatchedSKU" ){
		
		$(document).find(".member_view_menu").find("button").removeClass("active");
		element.addClass("active");
	
		var user_id = element.data("user_id");

		$(document).find(".formbox").find(".boxes").addClass("d-none");
		$(document).find(".formbox").find(".member_unmatched_box").removeClass("d-none");

		$(document).find(".member_unmatched_box").find("table tbody").empty();

		$(document).find(".member_unmatched_box").find("table tbody").append(
			"<tr>" +
				"<td class='text-center p-5' colspan='3'>Empty</td>" + 
			"</tr>"
		)

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "getViewUnmatchedSKU",
				user_id: user_id,
			},
			success: function(resp){	
				
				if( resp.unmatched_skus.length > 0 ){
					
					$(document).find(".member_unmatched_box").find("table tbody").empty();

					$.each(resp.unmatched_skus, function( k, v ){
						$(document).find(".member_unmatched_box").find("table tbody").append(
							"<tr class='sku_row' data-sku='" + v + "'>" +
								"<td>" + v + "</td>" + 
								"<td class='fit text-end' style='width: 50px;'>" + 
									"<button class='btn btn-primary btn-sm ebayintegration-btn'data-user_id='" + resp.user_id + "'  data-sku='" + v + "' data-action='addUnmatchedSKU'>" +
										"<i class='fa-solid fa-plus'></i>" +
									"</button>" +
								"</td>" + 
							"</tr>"
						);
					});
				}

				// defObject.resolve(resp);    //resolve promise and pass the response.
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});


	}	

	else if( jQuery(this).data("action") == "removeMemberSKU" ){		

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "removeMemberSKU",
				sku: $(this).data("sku"),
				user_id: $(this).data("user_id")
			},
			success: function(resp){	

				if( resp.error == false ){
					$(document).find(".member_sku_box").find("tbody tr.sku_row[data-sku='" + resp.sku + "']").remove();
				}
				
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});		

	}
	
	else if( jQuery(this).data("action") == "addUnmatchedSKU" ){

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "addUnmatchedSKU",
				sku: $(this).data("sku"),
				user_id: $(this).data("user_id")
			},
			success: function(resp){	

				if( resp.error == false ){
					$(document).find(".member_unmatched_box").find("tbody tr.sku_row[data-sku='" + resp.sku + "']").remove();
				}
				
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});		

	}
	
	else if( jQuery(this).data("action") == "deactivateMember" ){		

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "deactivateMember",
				user_id: $(this).data("user_id")
			},
			success: function(resp){	

				if( resp.error == false ){
					location.reload();
				}
				
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});		


	}

	else if( jQuery(this).data("action") == "saveMemberDetailsChanges" ){		

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "saveMemberDetailsChanges",
				user_id: $(this).data("user_id"),
				display_name: $(document).find(".member_details_box").find("[name='display_name']").val(),
				user_email: $(document).find(".member_details_box").find("[name='user_email']").val()
			},
			success: function(resp){	

				if( resp.error == false ){
					location.reload();
				}
				
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});		

	
	}
	else if( jQuery(this).data("action") == "consignmentPaidOut" ){		

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "consignmentPaidOut",
				id: $(this).data("id")
			},
			success: function(resp){	

				if( resp.error == false ){

					$(document).find(".ebay_card_row[data-id='" + resp.id + "'").remove();

					// location.reload();
				}
				
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});		
	
	}

	else if( jQuery(this).data("action") == "consignmentPaidOutQueue" ){		

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "consignmentPaidOutQueue",
				id: $(this).data("id")
			},
			success: function(resp){	

				console.log(resp);

				if( resp.error == false ){

					$(document).find(".ebay_card_row[data-id='" + resp.id + "'").css("border", "2px solid black");

					// location.reload();
				}
				
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});		
	
	}

	else if( jQuery(this).data("action") == "consignmentPaidOutRelease" ){		

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "consignmentPaidOutRelease",
				id: $(this).data("id")
			},
			success: function(resp){	

				console.log(resp);

				if( resp.error == false ){

					$(document).find(".ebay_card_row[data-id='" + resp.id + "'").css("border", "2px solid black");

					// location.reload();
				}
				
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});		
	
	}

	else if( jQuery(this).data("action") == "loginToAccount" ){		
		console.log( element.data() );

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "loginToAccount",
				data: element.data()
			},
			success: function(resp){	

				window.location.replace("/my-account");
				
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});		

	}		
	else if( jQuery(this).data("action") == "showMessageUserModal" ){		

		$(document).find(".member_info_modal").modal("hide");
		jQuery(document).find(".message_user_modal").prependTo('body').modal("show");

	}
	else if( jQuery(this).data("action") == "messageUser" ){		

		console.log( element.data() );

		jQuery.ajax({
			method: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: { 
				action: "messageUser",
				data: element.data()
			},
			success: function(resp){	

				console.log(resp);
				
			},
			error: function(){
				console.log("Error in AJAX");
			}
		});		

	}	

	else if( jQuery(this).data("action") == "cancelSendMessage" ){		
		
		$(document).find(".message_user_modal").modal("hide");
		jQuery(document).find(".member_info_modal").prependTo('body').modal("show");
		

	}

	else if( jQuery(this).data("action") == "sendUserMessage" ){	
		
		$(document).find(".message_user_modal").find(".formbox").addClass("d-none");
		$(document).find(".message_user_modal").find(".loading").removeClass("d-none");

		let form = new FormData( $(document).find(".message_user_modal").find("#message_user_form")[0] );
		
		$.ajax({
			type: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: form,
			enctype: 'multipart/form-data',
			processData: false,
			contentType: false,
			success: function(resp){

				$(document).find(".message_user_modal").modal("hide");
				jQuery(document).find(".member_info_modal").prependTo('body').modal("show");

				$(document).find(".message_user_modal").find(".formbox").removeClass("d-none");
				$(document).find(".message_user_modal").find(".loading").addClass("d-none");
		

			},
			error: function(){
				console.log("Error in AJAX");
			}
		});


	}
	
	else if( jQuery(this).data("action") == "newMember" ){		

		jQuery(document).find(".new_user_modal").prependTo('body').modal("show");

	}
	else if( jQuery(this).data("action") == "registerUser" ){		

		$(document).find(".new_user_modal").find(".formbox").addClass("d-none");
		$(document).find(".new_user_modal").find(".formbox").find(".error").addClass("d-none");
		$(document).find(".new_user_modal").find(".loading").removeClass("d-none");

		let form = new FormData( $(document).find(".new_user_modal").find("#register_user_form")[0] );

		$.ajax({
			type: 'post',
			url: "/wp-json/ebayintegration/v1/post",
			data: form,
			enctype: 'multipart/form-data',
			processData: false,
			contentType: false,
			success: function(resp){

				if( resp.error == false ){

					$(document).find(".new_user_modal").find(".formbox").removeClass("d-none");
					$(document).find(".new_user_modal").find(".loading").addClass("d-none");
			
	
					$(document).find(".new_user_modal").modal("hide");
	
				} else {


					$(document).find(".new_user_modal").find(".formbox").find(".error").removeClass("d-none");

					$(document).find(".new_user_modal").find(".loading").addClass("d-none");
					$(document).find(".new_user_modal").find(".formbox").removeClass("d-none");

				}


			},
			error: function(){
				console.log("Error in AJAX");
			}
		});		


	}
		
	else if( jQuery(this).data("action") == "refreshToken" ){		

		$(document).find(".pagebox").html("");


		jQuery.ajax({
			method: 'get',
			url: "/wp-json/ebayintegration/v1/ajax?action=refreshToken",
			success: function(resp){		

				var status = 'active';
				refreshStatus( status, "btn-primary" );

				var status = 'awaiting';
				refreshStatus( status, "btn-warning" );

				var status = 'sold';
				refreshStatus( status, "btn-success" );

				var status = 'unsold';
				refreshStatus( status, "btn-danger" );

			},
			error: function(){
				console.log("Error in AJAX");
			}
		});
	
	}

	else if( jQuery(this).data("action") == "refreshPage" ){		

		var status = element.data("status");
		var page = element.data("page");		

		refreshPage(status, page);

	}
	
	else {

		console.log("Action Not Set: " + jQuery(this).data("action") );

	}
	
});

// REFRESH ACTIONS 

function refreshPage( status, page ){

	$(document).find( "#" + status ).find(".pagebox").addClass("d-none");
	$(document).find( "#" + status ).find(".loading").removeClass("d-none");

	var url =  "/wp-json/ebayintegration/v1/ajax?action=getEbayItems&type=" + status + "&page=" + page

	jQuery.ajax({
		method: 'get',
		url: url,
		success: function(resp){		
			console.log( resp );

			if( resp.error == false ){

				$(document).find( "#" + status ).find(".pagebox").removeClass("d-none");
				$(document).find( "#" + status ).find(".loading").addClass("d-none");			

				$(document).find( "#" + status ).find(".pagebox")
					.find(".ebayintegration-btn[data-action='refreshPage'][data-page='" + resp.current_page + "']").remove();


				if( $(document).find( "#" + status ).find(".pagebox").find(".ebayintegration-btn[data-action='refreshPage']").length == 0 ){
					$(document).find( "#" + status ).find(".pagebox").append("Done");					
				}

			}

		},
		error: function(){
			console.log("Error in AJAX");
		}
	});

}

function refreshStatus(status, btn_color, page = null) {

	$(document).find( "#" + status ).find(".pagebox").addClass("d-none");
	$(document).find( "#" + status ).find(".loading").removeClass("d-none");
	
	if( page == null ){
		page = 1;
	} 
	var url =  "/wp-json/ebayintegration/v1/ajax?action=getEbayItems&type=" + status + "&page=" + page

	jQuery.ajax({
		method: 'get',
		url: url,
		success: function(resp){		
			console.log( resp );

			if( resp.error == false ){

				$(document).find( "#" + status ).find(".pagebox").removeClass("d-none");
				$(document).find( "#" + status ).find(".loading").addClass("d-none");			

				var i = 0;
				for( i = 1; i <= resp.pages; i++ ){

					$(document).find("#" + status ).find(".pagebox").append(
						"<button " +
							"class='ebayintegration-btn btn " + btn_color + " mb-3' " +
							"data-action='refreshPage'  " +
							"data-status='" + status + "'  " +
							"data-page='" + i + "'> " +
							i +
						"</button> "
					);

				}

			}

		},
		error: function(){
			console.log("Error in AJAX");
		}
	});


}


function showConsignedCardDetailsModal(id, user_id){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "showConsignedCardDetailsModal",
			id: id,
			user_id: user_id
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

function confirmUpdateConsignedCardDetails(){

	var defObject = $.Deferred();  // create a deferred object.

	var id = $(document).find(".consigned_card_details_modal").find("[name='id']").val();
	var user_id = $(document).find(".consigned_card_details_modal").find("[name='user_id']").val();
	var new_status = $(document).find(".consigned_card_details_modal").find("[name='new_status']").val();

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "confirmUpdateConsignedCardDetails",
			new_status: new_status,
			id: id,
			user_id: user_id
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

function confirmUnvailableConsignedCard(id, user_id){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "confirmUnvailableConsignedCard",
			id: id,
			user_id: user_id
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

function confirmConsignedCardReceivedAll(id, user_id){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "confirmConsignedCardReceivedAll",
			id: id,
			user_id: user_id
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

function consignedCardNotReceived(id, user_id){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "consignedCardNotReceived",
			id: id,
			user_id: user_id
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

function confirmConsignedCardReceived(id, user_id){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "confirmConsignedCardReceived",
			id: id,
			user_id: user_id
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

function removeConsignedCardRow(id, user_id){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "removeConsignedCardRow",
			id: id,
			user_id: user_id
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
	var player = $(document).find(".log_consign_modal").find(".formbox").find("[name='player']").val();
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
			player: player,
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


// Payout 

function confirmPayoutRequest(type, user_id){

	var defObject = $.Deferred();  // create a deferred object.

	let form = new FormData( $("#payout_request_form")[0] );

	$.ajax({
		type: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: form,
		enctype: 'multipart/form-data',
		processData: false,
		contentType: false,
		success: function(resp){
			defObject.resolve(resp);    //resolve promise and pass the response.
		},
		error: function(){
			console.log("Error in AJAX");
		}
	});

	return defObject.promise();

}

function getPayoutRequest($payout_id){

	var defObject = $.Deferred();  // create a deferred object.

	$.ajax({
		type: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: {
			"action": "getPayoutRequest",
			"payout_id": $payout_id
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





// GRADING

function confirmAddGrading(){

	var defObject = $.Deferred();  // create a deferred object.

	var user_id = parseInt( $(document).find(".log_grading_modal").find(".formbox").find("[name='user_id']").val() );
	var quantity = parseInt( $(document).find(".log_grading_modal").find(".formbox").find("[name='quantity']").val() );
	var year = $(document).find(".log_grading_modal").find(".formbox").find("[name='year']").val();
	var brand = $(document).find(".log_grading_modal").find(".formbox").find("[name='brand']").val();
	var player = $(document).find(".log_grading_modal").find(".formbox").find("[name='player']").val();
	var card_number = $(document).find(".log_grading_modal").find(".formbox").find("[name='card_number']").val();
	var attribute_sn = $(document).find(".log_grading_modal").find(".formbox").find("[name='attribute_sn']").val();
	var dv = $(document).find(".log_grading_modal").find(".formbox").find("[name='dv']").val();
	var max_dv = $(document).find(".log_grading_modal").find(".formbox").find("[name='max_dv']").val();
	var per_card = $(document).find(".log_grading_modal").find(".formbox").find("[name='per_card']").val();
	var grading_type = $(document).find(".log_grading_modal").find(".formbox").find("[name='grading_type']").val();

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "confirmAddGrading",
			user_id: user_id,
			quantity: quantity,
			year: year,
			brand: brand,
			player: player,
			card_number: card_number,
			attribute_sn: attribute_sn,
			dv: dv,
			max_dv: max_dv,
			per_card: per_card,
			grading_type: grading_type
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

function removeGradingCardRow(id, user_id){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "removeGradingCardRow",
			id: id,
			user_id: user_id
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

function confirmGradingTableClearList(type, user_id){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "confirmGradingTableClearList",
			type: type,
			user_id: user_id
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

function confirmGradingTableCheckout(type, user_id){

	var defObject = $.Deferred();  // create a deferred object.

	jQuery.ajax({
		method: 'post',
		url: "/wp-json/ebayintegration/v1/post",
		data: { 
			action: "confirmGradingTableCheckout",
			type: type,
			user_id: user_id
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



function Checker(){

	let to_check = $(document).find(".checker");
	let error_cnt = 0;

	$.each(to_check, function( k, v){

		if( to_check.eq( k ).data("checker") == "required" ){
			if( $(v).is("input") ){

				var type = to_check.eq( k ).attr( "type" );
				var value = "";

				switch( type ){

					case "text":

						value = to_check.eq( k ).val();
						break;

					case "checkbox":


						value = to_check.eq( k ).is(":checked");
						break;

					case "file":

						value = to_check.eq( k ).val();
						break;

					default:
						console.log( type );

				}

				if( value == false ){

					if( type != "checkbox"){

						$(to_check).eq( k ).css("background-color","#FFEFEF");
						$(to_check).eq( k ).addClass("checker-error");

					} else {

						$(to_check).eq( k ).css("border-color","red");
					}

					$(to_check).eq( k ).addClass("checker-error");
					error_cnt = error_cnt + 1;

				} else {

					if( type != "checkbox"){

						$(to_check).eq( k ).css("background","#ffffff");

					} else {

						$(to_check).eq( k ).css("border-color","gray");

					}

					$(to_check).eq( k ).removeClass("checker-error");


				}

			}
			else if( $(v).is("select") ){

				if( $(v).find("option:selected").val() == false ){

					$(to_check).eq( k ).css("background","#FFEFEF");
					$(to_check).eq( k ).addClass("checker-error");

					error_cnt = error_cnt + 1;

				}
				else {
					$(to_check).eq( k ).css("background","#ffffff");
					$(to_check).eq( k ).removeClass("checker-error");

				}

			}
		}

	} );

	if( error_cnt > 0 ){

		return false;
	}

	return true;

}


// ////////////////////// //
//   eBay API functions   //
// ////////////////////// //


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

// function getItemsRoutine(){

// 	var token = refreshAccessToken();
// 	var items = [];

// 	jQuery(document).find(".ebayintegration-items_box").find("#skus_table tbody").html(
// 		'<tr>' +
// 			'<td colspan="6" class="my-5 text-center">Getting Items From eBay</td>' +
// 		'</tr>'
// 	);

// 	$.when(token).done(function(response){

// 		console.log("Refreshed Access Token");

// 		if( response["token_type"] == "User Access Token" ){

// 			console.log("Get Item Pages - GO");

// 			var item_pages = getItemPages();

// 			$.when(item_pages).done( function(pages){

// 				var ListingDuration = [];
// 				var ListingType = [];
// 				var SKU = [];

// 				$.each( items, function( k, v ){

// 					if(jQuery.inArray( v.ListingDuration, ListingDuration) == -1)	{
// 						ListingDuration.push(v.ListingDuration);
// 					}					

// 					if(jQuery.inArray( v.ListingType, ListingType) == -1)	{
// 						ListingType.push(v.ListingType);
// 					}					

// 					if(jQuery.inArray( v.SKU, SKU) == -1){
// 						SKU.push(v.SKU);
// 					}					

// 				});

// 				jQuery(document).find(".ebayintegration-items_box").find("#skus_table tbody").empty();

// 				jQuery(document).find(".ebayintegration-items_box").find("#skus_table tbody").append( itemtemplate(pages.undefined_items) );
				

// 				$.each( pages.ebay_items, function( k, v ){
// 					console.log( v.ItemID );
// 				});


// 			});

// 		}

// 	});

// }

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


$(document).on("change", ".mobile_tab_select", function(){
	console.log($(this).val());

    window.location.href = $(this).val();
});

