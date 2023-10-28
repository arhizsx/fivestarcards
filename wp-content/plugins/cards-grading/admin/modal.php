<?php

$user_id = get_current_user_id();

?>
<div class="modal fade dxmodal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Add Card
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
			<div class="modal-body p-5">
				<input type="hidden" name="user_id" value='<?php echo $user_id; ?>'/>
				<input type="hidden" name="grading" value=''/>
				<div class="row">
					<div class="col-xl-6">
						<label for="quantity">Qty</label>
						<input type="number" name="quantity" class="form-control mb-2"/>
					</div>
					<div class="col-xl-6">
						<label for="quantity">Year</label>
						<input type="number" name="year" class="form-control mb-2"/>
					</div>
					<div class="col-xl-12">
						<label for="quantity">Brand</label>
						<input type="text" name="brand" class="form-control mb-2"/>
					</div>
					<div class="col-xl-12">
						<label for="quantity">Player Name</label>
						<input type="text" name="player" class="form-control mb-2"/>
					</div>
					<div class="col-xl-6">
						<label for="quantity">Attribute S/N</label>
						<input type="text" name="attribute" class="form-control mb-2"/>
					</div>
					<div class="col-xl-6">
						<label for="quantity">Declared Value</label>
						<input type="text" name="dv" class="form-control mb-2"/>
					</div>
				</div>
			</div>
			<div class="modal-footer">
		        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Cancel</button>
        		<button class="btn border btn-success 5star_btn btn-confirm">Add</button>
			</div>
		</div>
	</div>
</div>

<script>

alert('test');
function showAddCardModal( what_type ){
        
    $(document).find(".dxmodal").modal("show");
    $(document).find(".dxmodal").find("input[name='grading']").val(what_type);
    
}



$(document).on("click", ".5star_btn", function(e){
    e.preventDefault();

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
</script>

