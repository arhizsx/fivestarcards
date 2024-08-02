<?php 
$current_user = wp_get_current_user();
?>
<style>
    .floating-button-container {
        position: fixed;
        right: 15px;
        bottom: 5px;
        width: 54px;
    }
    .floating-button {
        opacity: 100%;
        cursor: pointer;
        margin-bottom: 5px;
        padding: 0;
        border: 1px solid white;
        border-radius: 35px; height: 39px; width: 39px;
        line-height: 39px;
        box-shadow: -0.46875rem 0 2.1875rem rgb(4 9 20 / 3%), -0.9375rem 0 1.40625rem rgb(4 9 20 / 3%), -0.25rem 0 0.53125rem rgb(4 9 20 / 5%), -0.125rem 0 0.1875rem rgb(4 9 20 / 3%);
    }
    .floating-button:hover {
        opacity: 100%;
    }
    .floating-buttons-hide, .floating-buttons-show {
        height: 20px; margin-bottom:30px; margin-top: -15px; cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-12">
        <H1 style="color: black;">
            Hello, <?php echo $current_user->display_name; ?>
        </H1> 
    </div>
    <div class="col-12 text-start">
    <button id="float_btn_add_ticket" class="btn btn-xl btn-primary ebayintegration-btn"  data-action="add_new_order">
        <i class="fa fa-circle-plus"></i> New Order
    </button>
        <!-- <small style="border: 1px solid white; background-color: black; color: white; padding: 5px; margin-right: 5px;">
            NEW ORDER
        </small>
        <button type="button" id="float_btn_add_ticket" class="floating-button btn btn-primary ebayintegration-btn" data-action="add_new_order" data-toggle="tooltip" data-placement="left" data-original-title="Add a Grading">
            <i class="fa fa-plus fa-2x fa-w-16 fa-beat" style="margin-top: 3px;"></i>
        </button> -->
    </div>
</div>

<div class="modal fade add_new_order_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title mb-0 p-0">
					New Order
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body p-3">
                <div class="row">
                    <div class="col">
                        <a class="btn border btn-primary form-control" href="/my-account/grading/new" >
                            Card Grading
                        </a>
                    </div>
                    <div class="col">
                        <a class="btn border btn-danger form-control" href="/my-account/consignment/new" >
                            Card Consignment
                        </a>
                    </div>
                </div>    


            </div>
		</div>
	</div>
</div>