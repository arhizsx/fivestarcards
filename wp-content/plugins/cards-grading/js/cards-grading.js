function showAddCardModal( what_type, per_card, max_dv ){
		
    $(document).find(".dxmodal").modal("show");
    $(document).find(".dxmodal").find("input[name='grading']").val(what_type);
    $(document).find(".dxmodal").find("input[name='per_card']").val(per_card);
    $(document).find(".dxmodal").find("input[name='max_dv']").val(max_dv);
    $(document).find(".dxmodal").find("button.btn_confirm").attr('data-type', what_type);

}


$(document).on("click", ".5star_btn", function(e){

    switch($(this).data("action")){		
        
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


            $(document).find(".dxmodal").find('#new_user_form *').filter(':input').each(function(){
                console.log($(this).val());
            });

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