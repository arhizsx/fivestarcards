function showAddCardModal( what_type, per_card, max_dv ){
		
    $(document).find(".dxmodal").modal("show");
    $(document).find(".dxmodal").find("input[name='grading']").val(what_type);
    $(document).find(".dxmodal").find("input[name='per_card']").val(per_card);
    $(document).find(".dxmodal").find("input[name='max_dv']").val(max_dv);
    $(document).find(".dxmodal").find("button.btn_confirm").attr('data-type', what_type);

}

function addCardToTable(card){

    
    console.log(card);
    console.log( $(document).find("table.5star_logged_cards").find("tbody row").html() );



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

                if( $(v).val().length > 0 ){

                    card[ $(v).attr("name") ] = $(v).val();

                } else {
                    $(v).focus();
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