function showAddCardModal( what_type, per_card, max_dv ){

    
    
    $(document).find(".dxmodal").find("div#add_card_form_box").removeClass("d-none");
    $(document).find(".dxmodal").find("div#maxed-out").addClass("d-none");
    $(document).find(".dxmodal").appendTo('body').modal("show");
    $(document).find(".dxmodal").find("input[name='grading']").val(what_type);
    $(document).find(".dxmodal").find("input[name='per_card']").val(per_card);
    $(document).find(".dxmodal").find("input[name='max_dv']").val(max_dv);

}

function addCardToTable(card){

    if( checkIfAddIsStillValid( card ) )
    {
    

        var nonce = $(document).find(".5star_logged_cards").data("nonce");
        var url = $(document).find(".5star_logged_cards").data("endpoint");

        $.ajax({
            method: 'post',
            url: url,
            headers: {'X-WP-Nonce': nonce },
            data: card,
            success: function(resp){

                var card_total_charge = parseFloat(card["quantity"]) * parseFloat(card["per_card"]);
                var card_total_dv = parseFloat(card["quantity"]) * parseFloat(card["dv"]);
            
                if( $(document).find(".5star_logged_cards tbody tr td:first-child").text() == "Empty"){
                    $(document).find(".5star_logged_cards tbody").empty();
                }
        
                var attribute = card["attribute"];
                if(card["attribute"] === undefined){
                    attribute = "";
                }
        
                $(document).find(".5star_logged_cards tbody").append(
                    "<tr class='card-row' data-post_id='" + resp + "'>" +
                        "<td>" + card["quantity"] + "</td>" +
                        "<td>" + card["year"] + "</td>" +
                        "<td>" + card["brand"] + "</td>" +
                        "<td>" + card["card_number"] + "<br><small>" + attribute + "</small>" + "</td>" +
                        "<td>" + card["player"] + "</td>" +
                        "<td class='text-end'><span class='dollar'>" + parseFloat(card["dv"]).toFixed(2) + "</span></td>" +
                        "<td class='text-end'><span class='dollar'>" + card_total_dv.toFixed(2) + "</span></td>" +
                        "<td class='text-end'><span class='dollar'>" + card_total_charge.toFixed(2) + "</span></td>" +
                    "</tr>"
                );
            
                clearModalForm();  
                setTotals(card_total_dv, card_total_charge)  
                
                $(document).find("div.bottom_buttons").removeClass("d-none");
                

            },
            error: function(){
                console.log("Error In Adding Encountered");
            }
        });





    } else {

        
        $(document).find("div#add_card_form_box").addClass("d-none");
        $(document).find("div#maxed-out").removeClass("d-none");
        $(document).find("div#maxed-out").find(".message").html(
            "<strong>Maximum allowed DV is only " + ( parseFloat(card["max_dv"]) - 1)  + "</strong>"
        );

    }
    

}

function setTotals( total_dv, grading_charge ){

    current_dv = parseFloat( $(document).find("#total_dv").text().replace("$",""));
    current_charge = parseFloat($(document).find("#grading_charges").text().replace("$",""));

    new_total_dv = total_dv + current_dv;
    new_grading_charge = grading_charge + current_charge;
    new_grand_total = new_total_dv + new_grading_charge;

    $(document).find("#total_dv").text( "$" + new_total_dv.toFixed(2) );
    $(document).find("#grading_charges").text( "$" + new_grading_charge.toFixed(2) );
    $(document).find("#grand_total").text( "$" + new_grand_total.toFixed(2) );

}

function checkIfAddIsStillValid( card ){

    var card_total_dv =  parseFloat(card["dv"]);

    var max_dv = card["max_dv"];

    if( card_total_dv < max_dv ) {
        return true;
    } else {
        return false;
    }

}

function clearModalForm(){
    $(document).find(".dxmodal").find('#add_card_form *').filter(':input:visible:enabled').each(function(k, v){
        $(v).val("");
    });    
}

function showClearTableModal(w){

    $(document).find(".clear_cards").find("div#clear_card_type_box").removeClass("d-none");
    $(document).find(".clear_cards").appendTo('body').modal("show");

}


function tableAction(what_type, action, what_modal){

    var nonce = $(document).find(".5star_logged_cards").data("nonce");
    var url = $(document).find(".5star_logged_cards").data("table_action_endpoint");

    $.ajax({
        method: 'post',
        url: url,
        headers: {'X-WP-Nonce': nonce },
        data: {
            'type' : what_type,
            'action' : action
        },
        success: function(resp){

            if(resp == true){
                if(action == "clear"){
                    $(document).find(".5star_logged_cards tbody").empty();
                    $(document).find(".5star_logged_cards tbody").append(
                        '<tr><td class="text-center" colspan="9">Empty</td></tr>'
                    );
                    $(document).find(".bottom_buttons").addClass("d-none");
                }
                else if(action == "checkout"){
                    console.log("Checkout Complete");
                }

                $(document).find(what_modal).modal("hide");
            }
            
        }
    });

}

function updateCard(){

    var nonce = $(document).find(".5star_logged_cards").data("nonce");
    var url = $(document).find(".5star_logged_cards").data("table_action_endpoint");
    
    var card = JSON.parse($(document).find("input[name='card']").val());
    var post_id = $(document).find("input[name='post_id']").val();
    var action = "update";

    $.ajax({
        method: 'post',
        url: url,
        headers: {'X-WP-Nonce': nonce },
        data: {
            'card' : card,
            'post_id': post_id,
            'action' : action
        },
        success: function(resp){

            $(document).find(".view_card").modal("hide");
            
        }
    });
}

function deleteCard(){

    var nonce = $(document).find(".5star_logged_cards").data("nonce");
    var url = $(document).find(".5star_logged_cards").data("table_action_endpoint");
    
    var card = JSON.parse($(document).find("input[name='card']").val());
    var post_id = $(document).find("input[name='post_id']").val();
    var action = "delete";

    $.ajax({
        method: 'post',
        url: url,
        headers: {'X-WP-Nonce': nonce },
        data: {
            'card' : card,
            'post_id': post_id,
            'action' : action
        },
        success: function(resp){

            $(document).find(".view_card").modal("hide");
            
        }
    });


}


function showShippedModal(w){

    $(document).find(".dxmodal").appendTo('body').modal("show");

}


function orderAction(){

    var nonce = $(document).find(".5star_logged_cards").data("nonce");
    var url = $(document).find(".5star_logged_cards").data("endpoint");
    var order_number = $(document).find("input[name='order_number']").val();

    $.ajax({
        method: 'post',
        url: url,
        headers: {'X-WP-Nonce': nonce },
        data: {
            'action' : action,
            'order_number': order_number
        },
        success: function(resp){

            console.log(resp);
            
        }
    });
    
}

$(document).on("click", ".5star_btn", function(e){

    console.log("button pressed");

    switch( $(this).data("action") ){		

        
        case "add_card" :

            console.log( $(this).data("type") );

            switch( $(this).data("type") ){

                case "psa-value_bulk":
                    showAddCardModal("psa-value_bulk", 19, 499);
                    break;

                case "psa-value_plus":
                    showAddCardModal("psa-value_plus", 40, 499);
                    break;

                case "psa-regular":
                    showAddCardModal("psa-regular", 75, 1499);
                    break;

                case "psa-express":
                    showAddCardModal("psa-express", 165, 2499);
                    break;

                case "psa-super_express":
                    showAddCardModal("psa-super_express", 330, 4999);
                    break;

                case "sgc-bulk":
                    showAddCardModal("sgc-bulk", 15, 1500);
                    break;


                case "cgc-bulk":
                    showAddCardModal("cgc-bulk", 15, 100);
                    break;

                case "cgc-economy":
                    showAddCardModal("cgc-economy", 25, 400);
                    break;

                case "cgc-standard":
                    showAddCardModal("cgc-standard", 35, 1000);
                    break;

                case "cgc-express":
                    showAddCardModal("cgc-express", 65, 10000);
                    break;

                default:

            }

            break;

        case "confirm_add":

            var error_cnt = 0;
            var card = {};

            $(document).find(".dxmodal").find('#add_card_form *').filter(':input').each(function(k, v){

                    if( $(v).val().length > 0 ){

                        card[ $(v).attr("name") ] = $(v).val();

                    } else {

                        if($(v).data("field_check") == "required"){

                            $(v).focus();
                            $(v).val('');
                            error_cnt = error_cnt + 1;
                            return false;

                        }
                }


            });

            if(error_cnt === 0){
                addCardToTable( card );
            }

            break;


        case "confirm_max_dv":
            $(document).find("#maxed-out").addClass("d-none");
            $(document).find("#add_card_form_box").removeClass("d-none");

            break;

        case "clear_table" :

            showClearTableModal();

            break;

        case "confirm_clear":

            tableAction( $(this).data("grading_type"), "clear", ".clear_cards" );

            break;

        case "checkout" :

            window.location.href = "/checkout?type=" + $(this).data("type") ;

            break;

        case "confirm_checkout":

            tableAction( $(this).data("type"), "checkout", ".checkout_cards" );

            break;

        case "update_card":

            updateCard();

            break;

        case "delete_card":

            deleteCard();

            break;

        case "shipped":

            showShippedModal();
            break;

        case "confirm_shipping":

            orderAction("set_shipping");
            break;

        default:
            console.log("Button not configured");
    }

});

$(document).on("click",".card-row", function(e){

    $(document).find(".view_card").find("div#view_card_form_box").removeClass("d-none");
    $(document).find(".view_card").appendTo('body').modal("show");

    $(document).find("input[name='quantity']").val($(this).data("card").quantity);
    $(document).find("input[name='year']").val($(this).data("card").year);
    $(document).find("input[name='brand']").val($(this).data("card").brand);
    $(document).find("input[name='card_number']").val($(this).data("card").card_number);
    $(document).find("input[name='player']").val($(this).data("card").player);
    $(document).find("input[name='attribute']").val($(this).data("card").attribute);
    $(document).find("input[name='dv']").val($(this).data("card").dv);
    $(document).find("input[name='per_card']").val($(this).data("card").per_card);
    $(document).find("input[name='grading']").val($(this).data("card").grading);
    $(document).find("input[name='max_dv']").val($(this).data("card").max_dv);
    $(document).find("input[name='post_id']").val($(this).data("post_id"));
    $(document).find("input[name='card']").val( JSON.stringify($(this).data("card")) );


});

$(document).on("click",".my-order-row", function(e){

    window.location.href = "/view-order?id=" + $(this).data("post_id") ;

});