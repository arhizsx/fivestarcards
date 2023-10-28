function showAddCardModal( what_type ){
		
    $(document).find(".dxmodal").modal("show");
    $(document).find(".dxmodal").find("input[name='grading']").val(what_type);
    
}

$(document).on("click", ".5star_btn", function(e){
    e.preventDefault();
    
    if($(this).hasClass("add_card")){			
        if( $(this).hasClass("psa_value_bulk") ){				
            showAddCardModal("psa_value_bulk");
        }			
        else if( $(this).hasClass("psa_value_plus") ){				
            showAddCardModal("psa_value_plus");
        }			
        else if( $(this).hasClass("psa_regular") ){				
            showAddCardModal("psa_regular");
        }			
        else if( $(this).hasClass("psa_express") ){				
            showAddCardModal("psa_express");
        }			
        else if( $(this).hasClass("psa_super_express") ){	
            showAddCardModal("psa_super_express");
        }			
    }
    
});
