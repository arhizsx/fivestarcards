<?php

$user_id = get_current_user_id();

?>

<style>
.modal-backdrop {
  z-index: -1;
}    
</style>

<div class="modal fade dxmodal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Log Card
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="add_card_form_box">
                    <div class="modal-body py-2 px-3">
                            <forn id="add_card_form">

                                <input type="hidden" name="user_id" value='<?php echo $user_id; ?>'/>
                                <input type="hidden" name="grading" value=''/>
                                <input type="hidden" name="max_dv" value=''/>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="quantity">Qty</label>
                                        <input type="number" name="quantity" value="1" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="year">Year</label>
                                        <input type="number" name="year" value=""  class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="brand">Brand</label>
                                        <input type="text" name="brand" value="" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="card_number">Card Number</label>
                                        <input type="text" name="card_number" value="" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="player">Player Name</label>
                                        <input type="text" name="player" value="" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="attribute">Attribute S/N</label>
                                        <input type="text" name="attribute" value="" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="per_card">Per Card</label>
                                        <input type="number" name="per_card" value=""  class="form-control mb-2" disabled/>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="dv">Declared Value</label>
                                        <input type="number" name="dv" value="" class="form-control mb-2"/>
                                    </div>
                                </div>
                            </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                        <button class="btn border btn-success 5star_btn" data-action='confirm_add' data-type=''>Log</button>
                    </div>
                </div>
                <div class="d-none text-center p-5" id="maxed-out">
                    <div class="modal-body py-2 px-3">
                        <i class="fa-solid fa-hand fa-6x"></i>
                        <div class="message py-3"></div>
                    </div>
                </div>
		</div>
	</div>
</div>