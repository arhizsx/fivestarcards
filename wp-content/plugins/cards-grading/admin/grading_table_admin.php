<?php

        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'status',
                    'value' => "Pending Customer Order"
                )
            ),
            'post_type' => 'cards-grading-chk',
            'posts_per_page' => -1
        );
        
        $posts = get_posts($args);

        $user_id=0;

        if($posts){
            foreach($posts as $post)
            {            
                $meta = get_post_meta($post->ID);
                $user = get_user_by( "id", $meta["user_id"][0] );                                                         
            }
        }
        

        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'name',
                    'value' => $meta["grading_type"][0]
                )
            ),
            'post_type' => 'cards-grading-type',
            'posts_per_page' => -1
        );
        
        $gradings = get_posts($args);

        if($gradings){
            foreach($gradings as $grading){
                $grading_meta = get_post_meta($grading->ID);                        
            }
        }

?>

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
            <select name="select_customer" class='btn me-4' style="border: 1px solid black">
                <option>Select Customer</option>
                <?php 
                    foreach( $users as $user){
                        // if( $user->roles[0] == 'um_member' ){
                            $id = $user->ID + 1000;
                            echo "<option value='" . $id . "'>" .  $user->display_name . '</option>';    
                        // }
                    }
                ?>
            </select>
            <span class="" style="font-size: 12px">Grading Type</span>
            <select name="select_grading_type" class='btn me-4' style="border: 1px solid black">
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
    <H3 style="color: black">Pending Assignment</H3>
    <div class="table-responsive">    
        <table class='table 5star_logged_cards table-bordered table-striped' data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/order-action") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Order #</th>
                    <th>Grading Type</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if( $posts ){
                    foreach($posts as $post){
                        $meta = get_post_meta($post->ID);

                        $date_format = get_option( 'date_format' );
                        $time_format = get_option( 'time_format' );

                        $user_id = $meta["user_id"][0];
                        $user = get_user_by( "id", $user_id );                                                                  

                ?>
                <tr class="" data-post_id="<?php echo $post->ID; ?>" data-card=''>
                    <td><?php echo get_the_date( $date_format, $post->ID ) ?><br><span style='font-size:.7em !important;'><?php echo get_the_time( $time_format, $post->ID ); ?></span></td>
                    <td><?php echo $user->display_name; ?><br> <small style="font-size: 11px;"><?php echo $user_id + 1000; ?></small></td>
                    <td><?php echo $meta["order_number"][0]; ?></td>
                    <td><?php echo $meta["grading_type"][0]; ?></td>
                    <td><?php echo $meta["status"][0]; ?></td>
                    <td class='text-end'>
                        <a class="btn btn-primary mb-3"  href="/admin/add-customer-order/?order_number=<?php echo $post->ID; ?>">
                            ...
                        </a>           
                    </td>
                </tr>
                <?php 
                    }   
                } 
                else {
                ?>
                <tr>
                    <td class="text-center" colspan="6">Empty</td>
                </tr>
                <?php 
                } 
                ?>
            </tbody>
        </table>
    </div>
    <?php 
        }
    ?>

    <?php
        if(isset($_GET["order_number"])) {
    ?>
        <div class="row mt-4 mb-5 ">
            <div class="col-xl-3 col-md-6">
                Order Number
                <input type="text" class="form-control" value="<?php echo $meta["order_number"][0]; ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                Customer
                <input type="text" class="form-control" value="<?php echo $user->display_name; ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                Grading Type
                <input type="text" class="form-control" value="<?php echo $meta["grading_type"][0]; ?>" disabled>
            </div>
            <div class="col-xl-3 col-md-6">
                Status
                <input type="text" class="form-control" value="<?php echo $meta["status"][0]; ?>" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-xl-6 col-md-6">
                <H2 style="color: black;">Cards List</H2>
            </div>
            <div class="col-xl-6 col-md-6 text-end">
                <button class="5star_btn btn btn-success" data-action="add_card" data-type="<?php echo $grading_meta["type"][0]; ?>">
                    Log Cards
                </button>           
            </div>
        </div>

        <div class="table-responsive">   
            <?php 
                $args = array(
                    'meta_query' => array(
                        array(
                            'key' => 'checkout_id',
                            'value' => $_GET['order_number']
                        )
                    ),
                    'post_type' => 'cards-grading-card',
                    'posts_per_page' => -1
                );

                $cards = get_posts($args);

            ?> 
            <table class='table 5star_logged_cards table-bordered table-striped' data-grading_type="<?php echo $meta['type'] ?>" data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/add-card") ?>" data-table_action_endpoint="<?php echo get_rest_url(null, "cards-grading/v1/table-action") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
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
                <?php
                if($cards){
                    foreach($cards as $card){

                        $cardmeta = get_post_meta($card->ID);
                        $active_card = json_decode($cardmeta['card'][0], true);

                        $card_total_dv = $active_card["dv"] * $active_card["quantity"];
                        $card_grading_charge = $active_card["per_card"] * $active_card["quantity"];
    
                        $grading_charge = $grading_charge + $card_grading_charge;
                        $total_dv = $total_dv + $card_total_dv;
                        
        
                ?>
                    <tr class="card-row" data-post_id="<?php echo $card->ID; ?>" data-card='<?php echo json_encode($active_card) ?>'>
                        <td>
                            <?php 
                                echo $active_card["year"]; 
                            ?>
                        </td>
                        <td><?php echo $active_card["brand"]; ?></td>
                        <td><?php echo $active_card["card_number"]; ?><br><small><?php echo $active_card["attribute"]; ?></small></td>
                        <td><?php echo $active_card["player"]; ?></td>
                        <td class='text-end'><?php echo "$" . number_format((float)$active_card["dv"], 2, '.', ''); ?></td>
                        <td class='text-end'><?php echo "$" . number_format((float) $card_grading_charge, 2, '.', ''); ?></td>
                    </tr>
                <?php 
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="6" class="text-center">Empty</td>
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
            <div class="row mb-4 border-top pt-3">
                <div class="col-12 text-end">
                    <a href="/admin/add-customer-order" class="5star_btn btn btn-secondary">
                        Go Back
                    </a>           
                    <button class="btn border btn-danger 5star_btn" data-action="admin_delete_order" data-order_number="<?php echo $_GET['order_number'] ?>" >Delete Order</button>
                        Delete Order
                    </button>           
                    <button class="5star_btn btn btn-primary">
                        Assign To Customer
                    </button>           
                </div>
            </div>
        </div>
    <?php 
        }
    ?>
</div>

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

                            <input type="hidden" name="checkout_id" value='<?php echo $_GET["order_number"]; ?>'/>
                            <input type="hidden" name="user_id" value='<?php echo $meta["user_id"][0]; ?>'/>
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

                                <input type="hidden" name="checkout_id" value='<?php echo $_GET["order_number"]; ?>'/>
                                <input type="hidden" name="user_id" value='<?php echo $meta["user_id"][0]; ?>'/>
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

<div class="modal fade delete_order" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Delete Order
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="view_card_form_box">
                    <div class="modal-body py-2 px-3">
                        <forn id="delete_order_form">

                            <input type="hidden" name="user_id" value='<?php echo $user_id; ?>'/>
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <label for="order_number">Order Number</label>
                                    <input id="order_number" type="number" name="order_number" style="font-size: 3em !important; text-align: center !important; color: white !important; background-color: red !important;"  value="<?php echo $_GET['order_number'] ?>" data-field_check="required" disabled  class="form-control mb-2"/>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    Are you sure you want to delete this order? This action cannot be reverted.
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                        <button class="btn border btn-danger 5star_btn" data-action="confirm_admin_delete_order" data-order_number="<?php echo $_GET['order_number'] ?>" data-back="/admin/add-customer-order/">Confirm Delete</button>
                    </div>
                </div>
		</div>
	</div>
</div>