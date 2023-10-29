<?php

$user_id = get_current_user_id();

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

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
                <div class="modal-body py-2 px-3">
                    <forn id="add_card_form">

                        <input type="hidden" name="user_id" value='<?php echo $user_id; ?>'/>
                        <input type="hidden" name="grading" value=''/>
                        <input type="hidden" name="per_card" value=''/>
                        <input type="hidden" name="max_dv" value=''/>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <label for="quantity">Qty</label>
                                <input type="number" name="quantity" value="1" class="form-control mb-2"/>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <label for="year">Year</label>
                                <input type="number" name="year" value="2020"  class="form-control mb-2"/>
                            </div>
                            <div class="col-xl-12">
                                <label for="brand">Brand</label>
                                <input type="text" name="brand" value="" class="form-control mb-2"/>
                            </div>
                            <div class="col-xl-12">
                                <label for="brand">Card Number</label>
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
                                <input type="text" name="per_card" value="" value='' disabled/>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <label for="dv">Declared Value</label>
                                <input type="text" name="dv" value="" class="form-control mb-2"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 mt-4 text-end fw-bold">
                                Total: $ <span class="add_total">0.00</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn border btn-secondary" data-bs-dismiss="modal" >Cancel</button>
                    <button class="btn border btn-success 5star_btn" data-action='confirm_add' data-type=''>Add</button>
                </div>
		</div>
	</div>
</div>