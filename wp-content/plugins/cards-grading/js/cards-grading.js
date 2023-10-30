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
    
        var card_total_charge = parseFloat(card["quantity"]) * parseFloat(card["per_card"]);
        var card_total_dv = parseFloat(card["quantity"]) * parseFloat(card["dv"]);
    
        if( $(document).find(".5star_logged_cards tbody tr td:first-child").text() == "Empty"){
            $(document).find(".5star_logged_cards tbody").empty();
        }

        var attribute = card["attribute"];
        if(card["attribute"] === undefined){
            attribute = "";
        }

        console.log(card["attribute"]);

        $(document).find(".5star_logged_cards tbody").append(
            "<tr>" +
                "<td>" + card["quantity"] + "</td>" +
                "<td>" + card["year"] + "</td>" +
                "<td>" + card["brand"] + "</td>" +
                "<td>" + card["card_number"] + "</td>" +
                "<td>" + card["player"] + "</td>" +
                "<td>" + attribute + "</td>" +
                "<td class='text-end'><span class='dollar'>" + parseFloat(card["dv"]).toFixed(2) + "</span></td>" +
                "<td class='text-end'><span class='dollar'>" + card_total_dv.toFixed(2) + "</span></td>" +
                "<td class='text-end'><span class='dollar'>" + card_total_charge.toFixed(2) + "</span></td>" +
            "</tr>"
        );
    
        clearModalForm();  
        setTotals(card_total_dv, card_total_charge)  

        var nonce = $(document).find(".5star_logged_cards").data("nonce");
        var url = $(document).find(".5star_logged_cards").data("endpoint");

        $.ajax({
            method: 'post',
            url: url,
            headers: {'X-WP-Nonce': nonce },
            data: card
        });


    } else {


        console.log(card);
        
        $(document).find("div#add_card_form_box").addClass("d-none");
        $(document).find("div#maxed-out").removeClass("d-none");
        $(document).find("div#maxed-out").find(".message").html(
            "<strong>Maximum allowed DV is only " + ( parseFloat(card["max_dv"]) - 1)  + "</strong>"
        );

        console.log( "Max DV Reached" );

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

function showClearTableModal(what_type){

    $(document).find(".clear_cards").find("div#clear_card_type_box").removeClass("d-none");
    $(document).find(".clear_cards").appendTo('body').modal("show");

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

            showClearTableModal( $(this).data("type"));

            break;

        case "checkout" :

            console.log("Checkout");

            break;

        default:
            console.log("Button not configured");
    }

});