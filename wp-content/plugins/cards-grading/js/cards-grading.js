function showAddCardModal( what_type ){
		
    jQuery(document).find(".dxmodal").modal("show");
    jQuery(document).find(".dxmodal").find("input[name='grading']").val(what_type);
    
}

jQuery(document).ready(function(){
    alert("test");
});

jQuery(document).on("click", ".5star_btn", function(e){
    e.preventDefault();

    if(jQuery(this).hasClass("add_card")){			

        switch( jQuery(this).data("type") ){

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
