function showAddCardModal( what_type, per_card, max_dv ){
		
    $(document).find(".dxmodal").modal("show");
    $(document).find(".dxmodal").find("input[name='grading']").val(what_type);
    $(document).find(".dxmodal").find("input[name='per_card']").val(per_card);
    $(document).find(".dxmodal").find("input[name='max_dv']").val(max_dv);
    
}


$(document).on("click", ".5star_btn", function(e){

    e.preventDefault();

    if($(this).data("action")== "add_card" ){			

        switch( $(this).data("type") ){

            case "psa-value_bulk":
                showAddCardModal("psa-value_bulk", 19, 100);
                break;

            case "psa-value_bulk":
                showAddCardModal("psa-value_plus", 19, 100);
                break;

            case "psa-regular":
                showAddCardModal("psa-regular", 19, 100);
                break;

            case "psa-express":
                showAddCardModal("psa-express", 19, 100);
                break;

            case "psa-super_express":
                showAddCardModal("psa-super_express", 19, 100);
                break;

            default:

        }
    }

});
