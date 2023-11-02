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
    
        if(parseInt(card["quantity"]) <= 0){

            $(document).find(".dxmodal").find('#add_card_form input[name="quantity"]').focus();

        } else {

            var nonce = $(document).find(".5star_logged_cards").data("nonce");
            var url = $(document).find(".5star_logged_cards").data("endpoint");
    
            if( $(document).find(".5star_logged_cards tbody tr td:first-child").text() == "Empty"){
                $(document).find(".5star_logged_cards tbody").empty();
            }
    
            var attribute = card["attribute"];
            if(card["attribute"] === undefined){
                attribute = "";
            }
            
            var quantity = parseInt(card["quantity"]);
    
            var i = 0;
    
            for ( i = 1; i <= quantity;  i++ ){
    
                card["quantity"] = 1;
    
                $.ajax({
                    method: 'post',
                    url: url,
                    headers: {'X-WP-Nonce': nonce },
                    data: card,
                    success: function(resp){
    
                        var card_total_charge = 1 * parseFloat(card["per_card"]);
                        var card_total_dv = 1 * parseFloat(card["dv"]);    
    
                        $(document).find(".5star_logged_cards tbody").prepend(
                            "<tr class='card-row' data-post_id='" + resp + "' data-card='" + JSON.stringify(card) + "'>" +
                                "<td>" + card["year"] + "</td>" +
                                "<td>" + card["brand"] + "</td>" +
                                "<td>" + card["card_number"] + "<br><small>" + attribute + "</small>" + "</td>" +
                                "<td>" + card["player"] + "</td>" +
                                "<td class='text-end'>$" + parseFloat(card["dv"]).toFixed(2) + "</td>" +
                                "<td class='text-end'>$" + card_total_charge.toFixed(2) + "</td>" +
                            "</tr>"
                        );    
    
                        clearModalForm();  
                        setTotals(parseInt(card["quantity"]), card_total_dv, card_total_charge)                                          
    
                    },
                    error: function(){
                        console.log("Error In Adding Encountered");
                    }
                });
                
            }
    
            $(document).find("div.bottom_buttons").removeClass("d-none");
    
        }


    } else {

        
        $(document).find("div#add_card_form_box").addClass("d-none");
        $(document).find("div#maxed-out").removeClass("d-none");
        $(document).find("div#maxed-out").find(".message").html(
            "<strong>Maximum allowed DV is only " + ( parseFloat(card["max_dv"]) - 1)  + "</strong>"
        );

    }
    

}

function setTotals( quantity,total_dv, grading_charge ){

    current_dv = parseFloat( $(document).find("#total_dv").text().replace("$",""));
    current_charge = parseFloat($(document).find("#grading_charges").text().replace("$",""));

    new_total_dv = quantity * total_dv + current_dv;
    new_grading_charge = quantity * grading_charge + current_charge;
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

                if(action == "clear"){
                    if(resp == true){
                        $(document).find(".5star_logged_cards tbody").empty();
                        $(document).find(".5star_logged_cards tbody").append(
                            '<tr><td class="text-center" colspan="6">Empty</td></tr>'
                        );
                        $(document).find(".bottom_buttons").addClass("d-none");
                    }
                }
                else if(action == "checkout"){

                    if(resp != false){

                        $(document).find(".5star_logged_cards tbody").empty();
                        $(document).find(".5star_logged_cards tbody").append(
                            '<tr><td class="text-center" colspan="6">Empty</td></tr>'
                        );
                        $(document).find(".bottom_buttons").addClass("d-none");
    
                        window.location.href = "/view-order?id=" + resp ;
    
                    }

                }

                $(document).find(what_modal).modal("hide");
            
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


function orderAction(action, data, order_number){

    var nonce = $(document).find(".5star_logged_cards").data("nonce");
    var url = $(document).find(".5star_logged_cards").data("endpoint");

    $.ajax({
        method: 'post',
        url: url,
        headers: {'X-WP-Nonce': nonce },
        data: {
            'action' : action,
            'order_number': order_number,
            'data': data
        },
        success: function(resp){

            if(resp ==true){
                $(document).find(".dxmodal").modal("hide");
                location.reload();

            } else {
                console.log("Set Shipping Failed");
            }

        }
    });
    
}

function cardAction(action, value, post_id, parent_element ){

    var nonce = $(document).find(".5star_logged_cards").data("nonce");
    var url = $(document).find(".5star_logged_cards").data("endpoint");

    $.ajax({
        method: 'post',
        url: url,
        headers: {'X-WP-Nonce': nonce },
        data: {
            'action' : action,
            'post_id': post_id,
            'value': value
        },
        success: function(resp){

            if(action == "card_update_status")
            {
                if(resp == true){
                    $(parent_element).find("td:eq(6)").text(value);                    
                } 

                var missing = 0;
                if( value == "Received" ){
                    $(parent_element).closest("table tbody").find("tr").each( function(k, v){
                        if( $(v).find("td:eq(6)").text() != "Received"){
                            missing = missing + 1;
                        }
                    });
                }

                if( value == "Not Available" ){
                    missing = missing + 1;
                }

                if( missing > 0 ){
                    $(document).find(".5star_btn[data-action='complete_package_contents']").addClass("d-none");
                    $(document).find(".5star_btn[data-action='incomplete_package_contents']").removeClass("d-none");
                } else {
                    $(document).find(".5star_btn[data-action='complete_package_contents']").removeClass("d-none");
                    $(document).find(".5star_btn[data-action='incomplete_package_contents']").addClass("d-none");
                }

            }
            else if(action == "set_grade")
            {
                $(document).find(".view_card").modal("hide");
                location.reload();
            }
            else if(action == "pay_card_grading")
            {
                if(resp == true){
                    $(parent_element).find("td:eq(4)").text(value);                    
                } 
            }            
        }
    });

}

function showSetGrade( data ){


    $(document).find(".view_card").appendTo('body').modal("show");

    $(document).find("#set_grade_form input[name='user_id']").val(data["card"]["user_id"]);
    $(document).find("#set_grade_form input[name='grading']").val(data["card"]["grading"]);
    $(document).find("#set_grade_form input[name='max_dv']").val(data["card"]["max_dv"]);
    $(document).find("#set_grade_form input[name='post_id']").val(data["post_id"]);
    $(document).find("#set_grade_form input[name='card']").val( JSON.stringify(data["card"]) );

    $(document).find("#set_grade_form input[name='year']").val(data["card"]["year"]);
    $(document).find("#set_grade_form input[name='dv']").val(data["card"]["dv"]);
    $(document).find("#set_grade_form input[name='brand']").val(data["card"]["brand"]);
    $(document).find("#set_grade_form input[name='card_number']").val(data["card"]["card_number"]);
    $(document).find("#set_grade_form input[name='player']").val(data["card"]["player"]);
    $(document).find("#set_grade_form input[name='attribute']").val(data["card"]["attribute"]);

    $('.view_card').on('shown.bs.modal', function () {
        $('#grade_input').focus();
    })

}

function confirmCardGrade(){

    var post_id =  $(document).find("#set_grade_form input[name='post_id']").val();
    var grade =  $(document).find("#set_grade_form input[name='grade']").val();

    cardAction("set_grade", grade, post_id, "");

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

            var error_cnt = 0;
            var shipping_info = {};

            $(document).find(".dxmodal").find('#shipping_info_form *').filter(':input').each(function(k, v){

                    if( $(v).val().length > 0 ){

                        shipping_info[ $(v).attr("name") ] = $(v).val();

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

                var order_number = $(document).find("input[name='order_number']").val();
                orderAction("set_shipping", shipping_info, order_number);
            }
 
            break;

        case "package_received":

            var order_number = $(this).data("order_number");
            if ( orderAction("package_received", null, order_number) ){
                location.reload();
            }


            break;

        case "item_not_avlb_in_package":

            cardAction("card_update_status", "Not Available", $(this).data("post_id"), $(this).closest("tr"));
            break;

        case "item_avlb_in_package":

            cardAction("card_update_status", "Received", $(this).data("post_id"), $(this).closest("tr"));
            break;

        case "complete_package_contents":

            var order_number = $(this).data("order_number");
            if ( orderAction("complete_package_contents", null, order_number) ){
                location.reload();
            }
            
            break;
            
        case "incomplete_package_contents":

            var order_number = $(this).data("order_number");
            if (orderAction("incomplete_package_contents", null, order_number) ){
                location.reload();
            }
            
            break;
            
        case "show_grades":
            var order_number = $(this).data("order_number");
            if (orderAction("show_grades", null, order_number) ){
                location.reload();
            }
            break;
        
        case "set_grade":

            showSetGrade( $(this).closest("tr").data() );

            break;

        case "confirm_card_grade":

            confirmCardGrade();
            break;    

        case "pay_card_grading":

            cardAction("card_update_status", "Pay Grading", $(this).data("post_id"), $(this).closest("tr"));
            break;

        default:
            console.log("Button not configured: " + $(this).data("action"));
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

$(document).on("click",".admin-order-row", function(e){

    window.location.href = "/admin/view-order?id=" + $(this).data("post_id") ;

});