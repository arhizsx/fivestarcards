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
                    <button class="btn border btn-success 5star_btn" data-action='confirm_add' data-type=''>Add</button>
                </div>
		</div>
	</div>
</div>

<H1>PSA - Value Bulk</H1>
<div class='5star_btn_box_top'>
  <button class='5star_btn btn btn-success mb-3' data-type="psa-value_bulk" data-action="add_card">
    Add Card
  </button>
</div>

<div class="table-responsive">
  
<table class='table 5star_logged_cards' data-grading_type="psa-value_bulk">
  <thead>
     <tr>
       <th>Qty</th>
       <th>Year</th>
       <th>Brand</th>
       <th>Card #</th>
       <th>Player Name</th>
       <th>Attribute S/N</th>
       <th>DV</th>
       <th>Total DV</th>
       <th>Total</th>
       <th>Action</th>
     </tr>
  </thead>
  <tbody>
    <tr>
      <td class="text-center" colspan="10">Empty</td>
    </tr>
  </tbody>
</table>
</div>
<div class='5star_btn_box_bottom w-100'>
  <div class="row">
    <div class="col-lg-6 text-end pb-2 fw-bold cards_dv_total">
    </div>
		<div class="col-lg-6 text-end pb-2 fw-bold cards_charge_total">
      <div class="row mb-2">
        <div class="col text-end">
					Total DV          
        </div>
        <div class="col text-end" id="total_dv">
        	$0.00   
        </div>
      </div>
      <div class="row">
        <div class="col text-end">
					Grading Charge    
        </div>
        <div class="col text-end"  id="total_charges">
        	$0.00   
        </div>
      </div>
    </div>
  </div>
	<div class="row">
    <div class="col-lg-12 text-end border-top pt-2">
  <button class='5star_btn btn btn-danger' data-type="psa-value_bulk" data-action="clear_table">
    Clear List
  </button>
      
      <button class='5star_btn btn btn-primary' data-type="psa-value_bulk" data-action="checkout">
        Checkout
      </button>      
    </div>
  </div>
  
</div>