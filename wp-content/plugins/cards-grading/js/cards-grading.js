function showAddCardModal( what_type ){
		
    $(document).find(".dxmodal").modal("show");
    $(document).find(".dxmodal").find("input[name='grading']").val(what_type);
    
}


$(document).on("click", ".5star_btn", function(e){

    e.preventDefault();

    console.log("Clicked")

    if($(this).hasClass("add_card")){			

        switch( $(this).data("type") ){

            case "psa-value_bulk":
                showAddCardModal("psa-value_bulk");
                break;

            case "psa-value_bulk":
                showAddCardModal("psa-value_plus");
                break;

            case "psa-regular":
                showAddCardModal("psa-regular");
                break;

            case "psa-express":
                showAddCardModal("psa-express");
                break;

            case "psa-super_express":
                showAddCardModal("psa-super_express");
                break;

            default:

        }
    }

});
