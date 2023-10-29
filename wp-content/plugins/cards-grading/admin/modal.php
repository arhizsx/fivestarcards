<?php

$user_id = get_current_user_id();

$args = array(
    'meta_query' => array(
        'relations' =>  'AND',    
        array(
            'key' => 'grading',
            'value' => $params['type']
        ),
        array(
            'key' => 'user_id',
            'value' => $user_id
        ),
        array(
            'key' => 'status',
            'value' => 'pending'
        )
    ),
    'post_type' => 'cards-grading-card',
    'posts_per_page' => -1
);

$posts = get_posts($args);

print_r($posts);

?>

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
<div class="pt-5 px-5 pb-0">
    <H1 style="color: black !important;"><?php echo $params['title'] ?></H1>
    <div class='5star_btn_box_top'>
    <button class='5star_btn btn btn-success mb-3' data-type="<?php echo $params['type'] ?>" data-action="add_card">
        Log Card
    </button>
    </div>

    <div class="table-responsive">
    
    <table class='table 5star_logged_cards' data-grading_type="<?php echo $params['type'] ?>" data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/add-card") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
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
    <button class='5star_btn btn btn-danger' data-type="<?php echo $params['type'] ?>" data-action="clear_table">
        Clear List
    </button>
        
        <button class='5star_btn btn btn-primary' data-type="<?php echo $params['type'] ?>" data-action="checkout">
            Checkout
        </button>      
        </div>
    </div>
    
    </div>
</div>