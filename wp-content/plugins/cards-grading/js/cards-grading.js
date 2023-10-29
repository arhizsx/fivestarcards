function showAddCardModal( what_type, per_card, max_dv ){
		
    $(document).find(".dxmodal").modal("show");
    $(document).find(".dxmodal").find("input[name='grading']").val(what_type);
    $(document).find(".dxmodal").find("input[name='per_card']").val(per_card);
    $(document).find(".dxmodal").find("input[name='max_dv']").val(max_dv);
    $(document).find(".dxmodal").find("button.btn_confirm").attr('data-type', what_type);

}

function addCardToTable(card){

    if( checkIfAddIsStillValid() )
    {
    
        var total_charge = parseFloat(card["quantity"]) * parseFloat(card["per_card"]);
        var total_dv = parseFloat(card["quantity"]) * parseFloat(card["dv"]);

        var current_dv = 300;


        if( current_dv + total_dv <=  card["max_dv"] ){

            if( $(document).find(".5star_logged_cards tbody tr td:first-child").text() == "Empty"){
                $(document).find(".5star_logged_cards tbody").empty();
            }
            
            $(document).find(".5star_logged_cards tbody").append(
                "<tr>" +
                    "<td>" + card["quantity"] + "</td>" +
                    "<td>" + card["year"] + "</td>" +
                    "<td>" + card["brand"] + "</td>" +
                    "<td>" + card["card_number"] + "</td>" +
                    "<td>" + card["player"] + "</td>" +
                    "<td>" + card["attribute"] + "</td>" +
                    "<td><span class='dollar'>" + parseFloat(card["dv"]).toFixed(2) + "</span></td>" +
                    "<td><span class='dollar'>" + total_dv.toFixed(2) + "</span></td>" +
                    "<td><span class='dollar'>" + total_charge.toFixed(2) + "</span></td>" +
                    "<td>Remove</td>" +
                "</tr>"
            );
        
            clearModalForm();
    
        } else {

            console.log( "Max DV Reached" );

        }

    }
    

}

function setTotals( total_dv, total_charge ){

}

function checkIfAddIsStillValid(){

    var total_dv = parseFloat( $(document).find("#total_dv").text() );
    var max_dv = 499;

    if( total_dv < max_dv ) {
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

$(document).on("click", ".5star_btn", function(e){

    console.log("button pressed");

    switch( $(this).data("action") ){		

        
        case "add_card" :

            switch( $(this).data("type") ){

                case "psa-value_bulk":
                    showAddCardModal("psa-value_bulk", 19, 499);
                    break;

                case "psa-value_bulk":
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

                default:

            }

            break;

        case "confirm_add":

            var error_cnt = 0;
            var card = {};

            $(document).find(".dxmodal").find('#add_card_form *').filter(':input').each(function(k, v){

                console.log( $(v).val() );

                if( $(v).val().length > 0 ){

                    card[ $(v).attr("name") ] = $(v).val();

                } else {
                    $(v).focus();
                    $(v).val('');
                    error_cnt = error_cnt + 1;
                    return false;
                }

            });

            if(error_cnt === 0){
                addCardToTable( card );
            }

            break;

        case "clear_table" :

            console.log("Clear Table");

            break;

        case "checkout" :

            console.log("Checkout");

            break;

        default:
            console.log("Button not configured");
    }

});