function showAddCardModal( what_type, per_card, max_dv ){

    
    
    $(document).find(".dxmodal").find("div#add_card_form_box").removeClass("d-none");
    $(document).find(".dxmodal").find("div#maxed-out").addClass("d-none");
    $(document).find(".dxmodal").appendTo('body').modal("show");
    $(document).find(".dxmodal").find("input[name='grading']").val(what_type);
    $(document).find(".dxmodal").find("input[name='per_card']").val(per_card);
    $(document).find(".dxmodal").find("input[name='max_dv']").val(max_dv);

}


$(document).on("click", ".5star_btn", function(e){

    console.log("button pressed");
    var element = $(this);
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

        case "acknowledge_missing_cards":

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


            // showSetGrade( $(this).closest("tr").data() );

            break;

        case "confirm_card_grade":

            confirmCardGrade();
            break;    

        case "pay_card_grading":

            cardAction("pay_card_grading", "Pay Grading", $(this).data("post_id"), $(this).closest("tr"));
            break;

        case "consign_card":

            cardAction("consign_card", "Consign Card", $(this).data("post_id"), $(this).closest("tr"));
            break;

        case "complete_grading_process":

            var order_number = $(this).data("order_number");
            if (orderAction("complete_grading_process", null, order_number) ){
                location.reload();
            }
        
            break;
        
        case "acknowledge_order_request":

            var order_number = $(this).data("order_number");
            if (orderAction("acknowledge_order_request", null, order_number) ){
                location.reload();
            }
        
            break;

        case "order_paid":

            showPaidModal();
        
            break;

        case "confirm_payment_info":

            var order_number = $(this).data("order_number");

            var mode_of_payment = $(document).find("[name='mode_of_payment'").val();
            var paid_by = $(document).find("[name='paid_by'").val();
            var payment_date = $(document).find("[name='payment_date'").val();
            var amount_paid = $(document).find("[name='amount_paid'").val();
            var reference_number = $(document).find("[name='reference_number'").val();

            var data = {
                "mode_of_payment" : mode_of_payment, 
                "paid_by": paid_by,
                "payment_date": payment_date,
                "amount_paid": amount_paid,
                "reference_number": reference_number,
            };

            if (orderAction("confirm_payment_info", data, order_number) ){
                // location.reload();
            }
    
            break;

        case "consignment_paid":

            showConsignmentPaidModal();
        
            break;

        case "confirm_consignment_payment":
            
            var order_number = $(this).data("order_number");

            var mode_of_payment = $(document).find("[name='mode_of_payment'").val();
            var paid_by = $(document).find("[name='paid_by'").val();
            var payment_date = $(document).find("[name='payment_date'").val();
            var amount_paid = $(document).find("[name='amount_paid'").val();
            var reference_number = $(document).find("[name='reference_number'").val();

            var data = {
                "mode_of_payment" : mode_of_payment, 
                "paid_by": paid_by,
                "payment_date": payment_date,
                "amount_paid": amount_paid,
                "reference_number": reference_number,
            };

            if (orderAction("confirm_consignment_payment", data, order_number) ){
                location.reload();
            }

            break;

        case "card_sold":
            
            showCardSoldModal($(this).data());

            break;

        case "confirm_sold_price":
            
            confirmSoldPrice();

            break;

        case "consignment_ready_for_payment":
            
            var order_number = $(this).data("order_number");
            if (orderAction("consignment_ready_for_payment", null, order_number) ){
                location.reload();
            }

            break;
    
        case "cancel_order":
            
            var order_number = $(this).data("order_number");
            if (orderAction("cancel_order", null, order_number) ){
                location.reload();
            }

            break;

        case "update_status":
            
            showUpdateStatusModal();
            break;

        case "set_submission_number":
            
            showSubmissionNumberModal();
            break;                        

        case "confirm_submission_number":
            
            var order_number = $(this).data("order_number");

            var customer_number = $(document).find("[name='customer_number'").val();
            var customer = $(document).find("[name='customer'").val();
            var submission_number = $(document).find("[name='submission_number'").val();

            var data = {
                "customer_number" : customer_number, 
                "customer": customer,
                "submission_number": submission_number,
            };

            if (orderAction("confirm_submission_number", data, order_number) ){
                location.reload();
            }
            break;                        
            
        case "confirm_new_order_status":
            
            var order_number = $(this).data("order_number");

            var customer_number = $(document).find("[name='customer_number'").val();
            var customer = $(document).find("[name='customer'").val();
            var old_status = $(document).find("[name='old_status'").val();
            var new_status = $(document).find("[name='new_status'").val();

            var data = {
                "customer_number" : customer_number, 
                "customer": customer,
                "old_status": old_status,
                "new_status": new_status,
            };

            if (orderAction("confirm_new_order_status", data, order_number) ){
                location.reload();
            }
            break;     

        case "remove_filter":
            
            var url = window.location.href.split('?')[0]
            location.href =url;

            break;

        case "admin_delete_order":

            $(document).find(".delete_order").appendTo('body').modal("show");
            
            break;

        case "confirm_admin_delete_order":

            var order_number = $(this).data("order_number");
            var back = $(this).data("back");
            var data = {"back" : back};
            
            if (orderAction("confirm_admin_delete_order", data, order_number) ){
                // location.href = back;
            }

            
            break;

        case "confirm_admin_delete_manual_order":

            var order_number = $(this).data("order_number");
            var back = $(this).data("back");
            var data = {"back" : back};
            
            if (orderAction("confirm_admin_delete_manual_order", data, order_number) ){
                // location.href = back;
            }

            
            break;

            
        case "admin_create_order":

            var user_id = $(document).find("[name='select_customer']").val();
            var grading_type = $(document).find("[name='select_grading_type']").val();

            var data = {"user_id": user_id, "grading_type": grading_type};

            var order_number = orderAction("admin_create_order", data, '');

            break;
            
        case "multi_update_status":
            
            var order_list = $(document).find("table.5star_my_orders tbody tr");
            var orders = [];

            $.each(order_list, function(){
                orders.push($(this).data("post_id"))
            });

            var new_status = $(document).find("select[name='multi_update_status_select ']").val();
            var data = {"new_status" : new_status};

            
            if (orderAction("multi_update_status", data, orders) ){

            }

            break;

        case "admin_assign_order":

            if (orderAction("admin_assign_order", "", $(this).data("order_number")) ){

            }

            break;

        case "view_pdf":

            var nonce = $(document).find(".5star_logged_cards").data("nonce");
            var url = $(document).find(".5star_logged_cards").data("view_pdf_endpoint");
            var order_number = $(this).data("order_number");
    
            window.open("/wp-json/cards-grading/v1/pdf?id=" + order_number, '_blank');
            break;

        case "set_skus":

            showSetSkus();
            break;

        case "make_admin":

            MakeAdmin(element.data("user_id"));


            break;

        case "demote_admin":

            DemoteAdmin(element.data("user_id"));
            break;

        case "view_account":

            let viewurl = $(document).find(".5star_table").data("endpoint");
            let view_user_id = $(this).data("user_id");
            let view_nonce = $(document).find(".5star_table").data("nonce");

            $(document).find(".view_account").appendTo('body').modal("show");

            $.ajax({
                method: 'post',
                url: viewurl,
                headers: {'X-WP-Nonce': view_nonce },
                data: {
                    'action' : "view_account",
                    'user_id': view_user_id,
                },
                success: function(resp){

                    console.log(resp);
        
                }
            });
        

            break;

        default:
            console.log("Button not configured: " + $(this).data("action"));
    }

});

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
                        window.location.href = "/my-account/grading/view-order/?mode=open&id=" + resp ;
    
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

            if(resp){
                location.reload();
            }
            
        }
    });


}

function orderAction(action, data, order_number){

    var nonce = $(document).find(".5star_logged_cards").data("nonce");

    var xurl = "";

    var override  = ["admin_create_order", "admin_assign_order", "confirm_admin_delete_manual_order"];


    if(  $.inArray(action, override)  >= 0 ){
        var xurl = $(document).find(".5star_logged_cards").data("order_endpoint");
    } 
    else {
        var xurl = $(document).find(".5star_logged_cards").data("endpoint");
    }

    $.ajax({
        method: 'post',
        url: xurl,
        headers: {'X-WP-Nonce': nonce },
        data: {
            'action' : action,
            'order_number': order_number,
            'data': data
        },
        success: function(resp){

            if(action == "admin_create_order"){
                
                location.href = "/administrator/grading/?mode=new&order_number=" + resp;
            } 
            else if(action == "admin_assign_order" && resp){
                location.href = "/administrator/grading/?mode=new";
            }
            else {

                if(resp ==true){
                    $(document).find(".dxmodal").modal("hide");
                    location.reload();
                }
                else if(resp.action == "back"){
                    $(document).find(".dxmodal").modal("hide");
                    location.href = resp.back;
    
                } else {
                    console.log("Order Action Failed");
                }
    
            }


        }
    });
    
}

function setGrade(post_id, grade, certificate_number){

    var nonce = $(document).find(".5star_logged_cards").data("nonce");
    var url = $(document).find(".5star_logged_cards").data("endpoint");

    $.ajax({
        method: 'post',
        url: url,
        headers: {'X-WP-Nonce': nonce },
        data: {
            'action': "set_grade",
            'post_id': post_id,
            'grade': grade,
            'certificate_number': certificate_number 
        },
        success: function(resp){
            // location.reload();
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
                var shipped = 0;

                if( value == "Received" ){
                    $(parent_element).closest("table tbody").find("tr").each( function(k, v){
                        if( $(v).find("td:eq(6)").text() != "Received"){
                            missing = missing + 1;
                        }
                        else if( $(v).find("td:eq(6)").text() == "Shipped"){
                            shipped = shipped + 1;
                        }
                    });
                }

                if( value == "Not Available" ){
                    missing = missing + 1;
                }

                if( missing > 0 && shipped == 0 ){
                    $(document).find(".5star_btn[data-action='complete_package_contents']").addClass("d-none");
                    $(document).find(".5star_btn[data-action='incomplete_package_contents']").removeClass("d-none");
                    $(document).find(".5star_btn[data-action='cancel_order']").removeClass("d-none");
                } else {
                    $(document).find(".5star_btn[data-action='complete_package_contents']").removeClass("d-none");
                    $(document).find(".5star_btn[data-action='incomplete_package_contents']").addClass("d-none");
                    $(document).find(".5star_btn[data-action='cancel_order']").addClass("d-none");

                }

            }
            else if(action == "set_grade")
            {
                if(resp == true){
                    $(document).find(".view_card").modal("hide");
                    location.reload();
                }
            }
            else if(action == "pay_card_grading")
            {
                if(resp == true){
                    location.reload();
                }


            }            
            else if(action == "consign_card")
            {
                if(resp == true){                    
                    location.reload();
                }
            }            
            else if(action == "confirm_sold_price")
            {
                if(resp == true){
                    location.reload();
                }


            }            
        }
    });

}

function showShippedModal(){

    $(document).find(".dxmodal").appendTo('body').modal("show");

}

function showPaidModal(){

    $(document).find(".paidmodal").appendTo('body').modal("show");

}

function showConsignmentPaidModal(){

    var to_pay_total = $(document).find("#to_pay_total").text();
    to_pay_total = parseFloat(to_pay_total.replace("$", ""));

    $(document).find("input[name='amount_paid']").val(to_pay_total);

    $(document).find(".consignmentpaidmodal").appendTo('body').modal("show");


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
        $('#grade').focus();
    })

}

function confirmCardGrade(){

    var post_id =  $(document).find("#set_grade_form input[name='post_id']").val();
    var grade =  $(document).find("#set_grade_form input[name='grade']").val();
    var certificate_number =  $(document).find("#set_grade_form input[name='certificate_number']").val();

    setGrade( post_id, grade, certificate_number );

}

function showCardSoldModal(data){

    console.log(data);

    $(document).find(".view_card").find("div#view_card_form_box").removeClass("d-none");
    $(document).find(".view_card").appendTo('body').modal("show");

    $(document).find("input[name='grade']").val(data["grade"]);
    $(document).find("input[name='quantity']").val(data["card"]["quantity"]);
    $(document).find("input[name='year']").val(data["card"]["year"]);
    $(document).find("input[name='brand']").val(data["card"]["brand"]);
    $(document).find("input[name='card_number']").val(data["card"]["card_number"]);
    $(document).find("input[name='player']").val( data["card"]["player"]);
    $(document).find("input[name='attribute']").val( data["card"]["attribute"]);
    $(document).find("input[name='dv']").val(  data["card"]["dv"]);
    $(document).find("input[name='per_card']").val(  data["card"]["per_card"]);
    $(document).find("input[name='grading']").val(  data["card"]["grading"]);
    $(document).find("input[name='max_dv']").val(  data["card"]["max_dv"]);
    $(document).find("input[name='post_id']").val(  data["post_id"]);
    $(document).find("input[name='card']").val( JSON.stringify( data["card"]) );

    $('.view_card').on('shown.bs.modal', function () {
        $('#sold_price').focus();
    });

}

function confirmSoldPrice(){

    var post_id =  $(document).find("#set_sold_price_form input[name='post_id']").val();
    var sold_price =  $(document).find("#set_sold_price_form input[name='sold_price']").val();

    cardAction("confirm_sold_price", sold_price, post_id, "");

}

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

    window.location.href = "/my-account/grading/view-order?mode=open&id=" + $(this).data("post_id") ;

});

$(document).on("click",".my-completed-row", function(e){

    window.location.href = "/my-account/view-my-completed?id=" + $(this).data("post_id") ;

});

$(document).on("click",".my-consignment-row", function(e){

    window.location.href = "/my-account/view-my-consignment?id=" + $(this).data("post_id") ;

});


$(document).on("click",".admin-order-row", function(e){

    if($(this).data("back") != ""){
        window.location.href = "/administrator/grading/view-order?id=" + $(this).data("post_id")  + "&mode=" + $(this).data("back") ;
    } else {
        window.location.href = "/administrator/grading/view-order?id=" + $(this).data("post_id");
    }    


});

$(document).on("click",".admin-payment-row", function(e){

    window.location.href = "/administrator/grading/view-payment?id=" + $(this).data("post_id") + "&mode=awaiting_payment" ;

});

$(document).on("click",".admin-consigned-row", function(e){

    window.location.href = "/admininistrator/view-consignment?id=" + $(this).data("post_id") ;

});

$(document).on("click",".admin-completed-row", function(e){

    window.location.href = "/administrator/grading/view-completed?id=" + $(this).data("post_id") ;

});

function showUpdateStatusModal(){

    $(document).find(".updatestatusmodal").appendTo('body').modal("show");

}

function showSubmissionNumberModal(){

    $(document).find(".setsubmissionmodal").appendTo('body').modal("show");

}


$(document).find('.setsubmissionmodal').on('shown.bs.modal', function() {
    $(document).find(".setsubmissionmodal").find("input[name='submission_number']").val("");
    $(document).find(".setsubmissionmodal").find("input[name='submission_number']").focus();
});

$(document).find('.view_card').on('shown.bs.modal', function() {
    $(document).find(".view_card").find("input[name='grade']").val("");
    $(document).find(".view_card").find("input[name='grade']").focus();
});

$(document).find('.setsubmissionmodal').on('shown.bs.modal', function() {
    $(document).find(".setsubmissionmodal").find("input[name='new_status']").val("");
    $(document).find(".setsubmissionmodal").find("input[name='new_status']").focus();
});


$(document).on("change", ".select_filter", function(){

    var data = $(this).data();
    var val = $(this).val();

    var urlParams = new URLSearchParams(window.location.search); //get all parameters
    var mode = urlParams.get('mode');
    var type = urlParams.get('type');
    var url = "";

    if( mode ){
        url = window.location.href + '&filtered=true&show=open&' + data['filter'] + '=' + val ;
    } 
    else {
        url = window.location.href + '?filtered=true&show=open&' + data['filter'] + '=' + val ;
    }

    location.href = url;



});


$(document).find(".search_box").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(document).find($(this).data("target") + " tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

function showSetSkus(){

    $(document).find(".set_skus_modal").appendTo('body').modal("show");

}


function MakeAdmin(user_id){

    var nonce = $(document).find(".5star_table").data("nonce");

    $.ajax({
        method: 'post',
        url: 'https://5starcards.com/wp-json/cards-grading/v1/table-action',
        headers: {'X-WP-Nonce': nonce },
        data: {
            'action' : "make_admin",
            'user_id': user_id,
        },
        success: function(resp){
            console.log(resp);
        }
    });

    location.reload();


}

function DemoteAdmin(user_id){
    
    var nonce = $(document).find(".5star_table").data("nonce");

    $.ajax({
        method: 'post',
        url: 'https://5starcards.com/wp-json/cards-grading/v1/table-action',
        headers: {'X-WP-Nonce': nonce },
        data: {
            'action' : "demote_admin",
            'user_id': user_id,
        },
        success: function(resp){
            console.log(resp);
        }
    });

    location.reload();
}


$(document).on("change", ".card_grade_saving", function(){

    var post_id = $(this).data("post_id");
    var db_id = $(this).data("db_id");
    var name = $(this).data("name");
    var value = $(this).val();


    var grading = saveGrading( post_id, db_id, name, value );

    $(document).find(".admin-card-row[data-post_id='" + post_id + "']").find("input[name='certificate_number']").prop("disabled", true);

    $.when( grading ).done( function( grading ){

        if( grading.error == false ){

            $(document).find(".admin-card-row[data-post_id='" + post_id + "']").find("input[name='certificate_number']").prop("disabled", false);

        } else {

            $(document).find(".admin-card-row[data-post_id='" + post_id + "']").remove();

            if( $(document).find("#card_table tbody tr").length == 0 ){

                $(document).find("#card_table tbody").append(
                    "<tr class='p-5 text-center'>Empty</tr>"
                );  
            }

            

            $(document).find("#completed_graded tbody").append(
                "<tr class='admin-graded-row' data-post_id='" + post_id + "'>" +
                    "<td>ID</td>" + 
                    "<td>TITLE</td>" + 
                    "<td>GRADE</td>" + 
                    "<td>SERIAL</td>" + 
                "</tr>"
            );

        }

        
        // psa = JSON.parse(JSON.stringify(grading.psa));
        console.log( grading.psa["certImgBack"]);
        // console.log( psa.table_data["Year"] + " " +  psa.table_data["Brand"] +  " " + psa.table_data["Card Number"] + " " + psa.table_data["Player"]  + " " + psa.table_data["Variety/Pedigree"]  + " " + psa.table_data["Grade"] );

    });

});

function saveGrading(post_id, db_id, name, value){

	var defObject = $.Deferred();  // create a deferred object.

    $.ajax({
        type: 'post',
        url: "/wp-json/ebayintegration/v1/post",
        data: { 
            action: "card_grade_saving",
            name: name,
            post_id: post_id,
            db_id: db_id,
            value: value
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