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
                                <input type="hidden" name="card" value=''/>

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
                        <button class="btn border btn-danger 5star_btn" data-action="delete_card">Delete</button>
                        <button class="btn border btn-primary 5star_btn" data-action="update_card">Update</button>
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
    <?php
        if(isset($_GET["order_number"]) == false) {
    ?>
        <div class="row mb-5 mt-3">
            <div class="col-xl-12 new_order_fields">
                <span class="" style="font-size: 12px">Customer</span>
                <?php 

                    $args = array(
                        'orderby'    => 'display_name',
                        'order'      => 'ASC'
                    );   

                    $users = get_users( $args );

                ?>
                <select class='btn me-4' style="border: 1px solid black">
                    <option>Select Customer</option>
                    <?php 
                        foreach( $users as $user){
                            if( $user->roles[0] == 'um_member' ){
                                $id = $user->ID + 1000;
                                echo "<option value='" . $id . "'>" .  $user->display_name . '</option>';    
                            }
                        }
                    ?>
                </select>
                <span class="" style="font-size: 12px">Grading Type</span>
                <select class='btn me-4' style="border: 1px solid black">
                    <option>Select Grading Type</option>
                    <option value="psa-value_bulk">PSA - Value Bulk</option>
                    <option value="psa-value_plus">PSA - Value Plus</option>
                    <option value="psa-regular">PSA - Regular</option>
                    <option value="psa-express">PSA - Express</option>
                    <option value="psa-super_express">PSA - Super Express</option>
                    <option value="sgc-bulk">SGC - Bulk</option>
                </select>
                <button class='5star_btn btn btn-primary btn-sm' data-type="" data-action="admin_create_order">
                    Create New Order
                </button>
            </div>
        </div>
    <?php 
        }
    ?>

    <?php
        if(isset($_GET["order_number"])) {
    ?>
        <div class="add_customer_order_log_cards">
            <div class="row mt-4 mb-5 ">
                <div class="col-xl-4">
                    Order Number
                    <input type="text" class="form-control">
                </div>
                <div class="col-xl-4">
                    Customer
                    <input type="text" class="form-control">
                </div>
                <div class="col-xl-4">
                    Grading Type
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <H2 style="color: black;">Cards List</H2>
                </div>
                <div class="col-xl-6 text-end">
                    <button class="5star_btn btn btn-success" data-action="log_card">
                        Log Cards
                    </button>           
                </div>
            </div>
            <div class="table-responsive">    
                <table class='table 5star_logged_cards table-bordered table-striped' data-grading_type="" data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/add-card") ?>" data-table_action_endpoint="<?php echo get_rest_url(null, "cards-grading/v1/table-action") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
                    <thead>
                        <tr>
                        <th>Year</th>
                        <th>Brand</th>
                        <th>Card #</th>
                        <th>Player Name</th>
                        <th class='text-end'>DV</th>
                        <th class="text-end">Grading</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="card-row" data-post_id="" data-card=''>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $card["player"]; ?></td>
                            <td class='text-end'></td>
                            <td class='text-end'></td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="9">Empty</td>
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
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-end">
                            Grading Charge    
                        </div>
                        <div class="col text-end"  id="grading_charges">
                        
                        </div>
                    </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <button class="5star_btn btn btn-secondary" data-action="log_card">
                            Cancel Order
                        </button>           
                        <button class="5star_btn btn btn-primary" data-action="log_card">
                            Add To Customer
                        </button>           
                    </div>
                </div>
            </div>
        </div>
    <?php 
        }
    ?>
</div>

<div class="modal fade admin_add_customer_order" data-show="true" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
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