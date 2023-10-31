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
$grading_charge = 0;
$total_dv = 0;


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
                                        <input type="number" name="quantity" value="1" data-field_check="required" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="year">Year</label>
                                        <input type="number" name="year" value="" data-field_check="required"  class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="brand">Brand</label>
                                        <input type="text" name="brand" value="" data-field_check="required" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="card_number">Card Number</label>
                                        <input type="text" name="card_number" value="" data-field_check="required" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="player">Player Name</label>
                                        <input type="text" name="player" value="" data-field_check="required" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="attribute">Attribute S/N</label>
                                        <input type="text" name="attribute" value="" data-field_check="" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="per_card">Per Card</label>
                                        <input type="number" name="per_card" value="" data-field_check="required"  class="form-control mb-2" disabled/>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="dv">Declared Value</label>
                                        <input type="number" name="dv" value="" data-field_check="required" class="form-control mb-2"/>
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
                        <button class="btn border btn-dark 5star_btn px-5" data-action="confirm_max_dv">OK</button>
                    </div>
                </div>
		</div>
	</div>
</div>


<div class="modal fade clear_cards" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="clear_cards">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-danger text-white">
				<h5 class="modal-title">
					Clear Logged Cards
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="" id="clear_card_type_box">
                <div class="modal-body text-center p-5">
                    Do you really want to clear your logged cards?
                </div>
                <div class="modal-footer">
                    <button class="btn border btn-secondary" data-bs-dismiss="modal" >Cancel</button>
                    <button class="btn border btn-danger 5star_btn" data-action='confirm_clear'  data-grading_type="<?php echo $params['type'] ?>">Clear</button>
                </div>
            </div>
		</div>
	</div>
</div>

<div class="modal fade checkout_cards" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="clear_cards">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title">
					Checkout Logged Cards
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="" id="checkout_card_type_box">
                <div class="modal-body text-center p-5">
                    Do you really want to checkout your logged cards?
                </div>
                <div class="modal-footer">
                    <button class="btn border btn-secondary" data-bs-dismiss="modal" >Cancel</button>
                    <button class="btn border btn-primary 5star_btn" data-action='confirm_checkout'  data-grading_type="<?php echo $params['type'] ?>">Checkout</button>
                </div>
            </div>
		</div>
	</div>
</div>

<div class="modal fade view_card" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Logged Card Info
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="view_card_form_box">
                    <div class="modal-body py-2 px-3">
                            <forn id="add_card_form">

                                <input type="hidden" name="user_id" value='<?php echo $user_id; ?>'/>
                                <input type="hidden" name="grading" value=''/>
                                <input type="hidden" name="max_dv" value=''/>
                                <input type="hidden" name="post_id" value=''/>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="quantity">Qty</label>
                                        <input type="number" name="quantity" value="1" data-field_check="required" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="year">Year</label>
                                        <input type="number" name="year" value="" data-field_check="required"  class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="brand">Brand</label>
                                        <input type="text" name="brand" value="" data-field_check="required" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="card_number">Card Number</label>
                                        <input type="text" name="card_number" value="" data-field_check="required" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="player">Player Name</label>
                                        <input type="text" name="player" value="" data-field_check="required" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="attribute">Attribute S/N</label>
                                        <input type="text" name="attribute" value="" data-field_check="" class="form-control mb-2"/>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="per_card">Per Card</label>
                                        <input type="number" name="per_card" value="" data-field_check="required"  class="form-control mb-2" disabled/>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <label for="dv">Declared Value</label>
                                        <input type="number" name="dv" value="" data-field_check="required" class="form-control mb-2"/>
                                    </div>
                                </div>
                            </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                        <button class="btn border btn-danger">Delete</button>
                        <button class="btn border btn-primary">Update</button>
                    </div>
                </div>
                <div class="d-none text-center p-5" id="error">
                    <div class="modal-body py-2 px-3">
                        <i class="fa-solid fa-hand fa-6x"></i>
                        <div class="message py-3"></div>
                        <button class="btn border btn-dark 5star_btn px-5" data-action="confirm_max_dv">OK</button>
                    </div>
                </div>
		</div>
	</div>
</div>

<!-- table for grading -->

<div class="m-0 p-0">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6" >
            <H1 style="color: black !important;"><?php echo $params['title'] ?></H1>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 text-end">
            <button class='5star_btn btn btn-success mb-3' data-type="<?php echo $params['type'] ?>" data-action="add_card">
                Log Card
            </button>
        </div>
    </div>
    <div class="table-responsive">
    
    <table class='table 5star_logged_cards table-bordered table-striped' data-grading_type="<?php echo $params['type'] ?>" data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/add-card") ?>" data-table_action_endpoint="<?php echo get_rest_url(null, "cards-grading/v1/table-action") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
    <thead>
        <tr>
        <th>Qty</th>
        <th>Year</th>
        <th>Brand</th>
        <th>Card #</th>
        <th>Player Name</th>
        <th>Attribute S/N</th>
        <th class='text-end'>DV</th>
        <th class='text-end'>Total DV</th>
        <th class="text-end">Grading Total</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            if( $posts ){

                foreach($posts as $post)
                {
                    $meta = get_post_meta($post->ID);
                    $card = json_decode($meta['card'][0], true);

                    $card_total_dv = $card["dv"] * $card["quantity"];
                    $card_grading_charge = $card["per_card"] * $card["quantity"];

                    $grading_charge = $grading_charge + $card_grading_charge;
                    $total_dv = $total_dv + $card_total_dv;

        ?>
        <tr class="card-row" data-post_id="<?php echo $post->ID; ?>" data-card='<?php echo json_encode($card) ?>'>
            <td><?php echo $card["quantity"]; ?></td>
            <td><?php echo $card["year"]; ?></td>
            <td><?php echo $card["brand"]; ?></td>
            <td><?php echo $card["card_number"]; ?></td>
            <td><?php echo $card["player"]; ?></td>
            <td><?php echo $card["attribute"]; ?></td>
            <td class='text-end'><?php echo "$" . number_format((float)$card["dv"], 2, '.', ''); ?></td>
            <td class='text-end'><?php echo "$" . number_format((float) $card_total_dv, 2, '.', ''); ?></td>
            <td class='text-end'><?php echo "$" . number_format((float) $card_grading_charge, 2, '.', ''); ?></td>
        </tr>
        <?php          
                }
            } else {
        ?>
        <tr>
            <td class="text-center" colspan="10">Empty</td>
        </tr>
        <?php          
            }
        ?>
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
                $<?php echo number_format((float)$total_dv, 2, '.', ''); ?>
            </div>
        </div>
        <div class="row">
            <div class="col text-end">
                        Grading Charge    
            </div>
            <div class="col text-end"  id="grading_charges">
            $<?php echo number_format((float)$grading_charge, 2, '.', ''); ?>
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