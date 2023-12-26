<?php


$checkout_post = get_post($params['order_number']);
$checkout_meta = get_post_meta($checkout_post->ID);

$user_id = $checkout_meta["user_id"][0];
$user = get_user_by( "id", $user_id );

$args = array(
    'meta_query' => array(
        'relations' =>  'AND',    
        array(
            'key' => 'checkout_id',
            'value' => $params['order_number']
        )
    ),
    'post_type' => 'cards-grading-card',
    'posts_per_page' => -1
);

$posts = get_posts($args);

$cards_count = 0;
$total_dv = 0;

foreach($posts as $post)
{
    $meta = get_post_meta($post->ID);
    $card = json_decode($meta['card'][0], true);

    $card_total_dv = $card["dv"] * $card["quantity"];

    $total_dv = $total_dv + $card_total_dv;
    $cards_count = $cards_count + $card["quantity"];
}

$admin_status = array( "Shipped", "Package Received", "Incomplete Items Shipped" );
$admin_action_status = array( "Package Received", "Processing Order" );

$processed_status = array("Processing Order", "Cards Graded");

?>

<div class="m-0 p-0">
    <div class="row border-bottom">
        <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12" >
            <div class="row">
                <div class="col-xl-12 mb-3">
                    <div class='order-label'><?php echo $params['title'] ?></div>
                    <div class='order-data'><?php echo $params['order_number'] ?></div>
                </div>
                <div class="col-xl-12 mb-3">
                    <div class='order-label'>User</div>
                    <div class='order-data'><?php echo $user->display_name; ?></div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-lg-12 col-md-12  col-sm-12" >

            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Status</div>
                    <div class='order-data'><?php echo $checkout_meta["status"][0] ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Service Type</div>
                    <div class='order-data'><?php echo $checkout_meta["service_type"][0] ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Grading Type</div>
                    <div class='order-data'><?php echo $checkout_meta["grading_type"][0] ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Total Declared Value</div>
                    <div class='order-data'>$<?php echo number_format((float)$total_dv, 2, '.', ''); ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Carrier</div>
                    <div class='order-data'><?php echo $checkout_meta["carrier"][0] ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Shipped By</div>
                    <div class='order-data'><?php echo $checkout_meta["shipped_by"][0] ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Tracking Number</div>
                    <div class='order-data'><?php echo $checkout_meta["tracking_number"][0] ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Shipping Date</div>
                    <div class='order-data'><?php echo $checkout_meta["shipping_date"][0] ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Total Cards</div>
                    <div class='order-data'><?php echo $cards_count; ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Consigned Cards</div>
                    <div class='order-data'>0</div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Submission #</div>
                    <div class='order-data'>0</div>
                </div>
            </div>

        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <H3 style="color: black !important;">Cards List</H3>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 text-end">
            <?php if( $checkout_meta["status"][0] == "Shipped" ) { ?>
            <button class='5star_btn btn btn-primary mb-3' data-action="package_received"  data-order_number="<?php echo $params['order_number'] ?>">
                Package Received
            </button>      
            <?php } ?>
            <?php 
            if( $checkout_meta["status"][0] == "Package Received" ) { 

                if( $posts )
                {
                    $received = 0;
                    $missing = 0;
                    $shipped = 0;

                    foreach($posts as $post)
                    {
                        $meta = get_post_meta($post->ID);
                        if( $meta["status"][0] == "Received" ){
                            $received++;
                        }
                        elseif( $meta["status"][0] == "Not Available" ){
                            $missing++;
                        }
                        elseif( $meta["status"][0] == "Shipped" ){
                            $shipped++;
                        }
                    }

                    if( $received == count($posts) && $shipped == 0){
                        $complete_btn = "";
                    } else {
                        $complete_btn = "d-none";
                    }

                    if( $missing > 0 && $shipped == 0){
                        $missing_btn = "";
                    } else {
                        $missing_btn = "d-none";
                    }
                }
            ?>
            <button class='5star_btn btn btn-danger mb-3 <?php echo $missing_btn; ?>' data-action="cancel_order" data-order_number="<?php echo $params['order_number'] ?>">
                Cancel Order
            </button>      
            <button class='5star_btn btn btn-warning mb-3 <?php echo $missing_btn; ?>' data-action="incomplete_package_contents" data-order_number="<?php echo $params['order_number'] ?>">
                Missing Items
            </button>      
            <button class='5star_btn btn btn-primary mb-3 <?php echo $complete_btn; ?>' data-action="complete_package_contents" data-order_number="<?php echo $params['order_number'] ?>">
                Items Complete
            </button>      
            <?php } ?>

            <?php 
            if( $checkout_meta["status"][0] == "Processing Order" ) 
            { 
                $graded = 0;
                $not_avlb = 0;

                foreach($posts as $post)
                {
                    $meta = get_post_meta($post->ID);
                    if( $meta["status"][0] == "Graded" ){
                        $graded++;
                    }
                    elseif( $meta["status"][0] == "Not Available" ){
                        $not_avlb++;
                    }
                }

                if( count($posts) > $graded + $not_avlb ){
                    $show_grade_btn = "d-none";
                } 
                elseif( count($posts) == $not_avlb ){
                    $show_grade_btn = "d-none";
                    $show_cancel_btn = "";
                } else {
                    $show_grade_btn = "";
                    $show_cancel_btn = "d-none";
                }

            
            ?>
            <button class='5star_btn btn btn-primary mb-3 <?php echo $show_grade_btn; ?>' data-action="show_grades" data-order_number="<?php echo $params['order_number'] ?>">
                Show Grades
            </button>      
            <?php 
            } 
            ?> 

            <?php 
            if( $checkout_meta["status"][0] == "Grading Complete" ) 
            { 
            ?>
            <button class='5star_btn btn btn-success mb-3' data-action="acknowledge_order_request" data-order_number="<?php echo $params['order_number'] ?>">
                Acknowledge Order Request
            </button>      
            <?php 
            } 
            ?> 

        </div>
    </div>
    <div class="table-responsive">   
        <table class='table table-sm 5star_logged_cards table-bordered table-striped' data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/order-action") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
            <thead>
                <tr>
                    <?php if( in_array( $checkout_meta["status"][0], $admin_action_status ) ){ ?>
                        <?php 
                            if( $checkout_meta["status"][0] == "Package Received" ) { 
                                $action_label = "Inside Package";
                            } else {
                                $action_label = "Action";
                            }
                        ?>
                    <th><?php  echo $action_label; ?></th>
                    <?php } ?>
                    <th>ID</th>
                    <th>Year</th>
                    <th>Brand</th>
                    <th>Card #</th>
                    <th>Player Name</th>
                    <th>Status</th>
                    <?php if( in_array( $checkout_meta["status"][0], $processed_status ) ){ ?>
                    <th class="text-end">Grade</th>
                    <?php } ?>
                    <th class='text-end'>DV</th>
                    <th class="text-end">Grading</th>
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

                            if( in_array($meta["status"][0], array("To Pay - Grade Only", "Shipped", "To Ship", "Received", "Pay Grading") ) ){
                                $grading_charge = $grading_charge + $card_grading_charge;
                            }


                ?>
                <tr class="admin-card-row" data-post_id="<?php echo $post->ID; ?>" data-card='<?php echo json_encode($card) ?>'>
                    <?php if( in_array( $checkout_meta["status"][0], $admin_action_status ) ){ ?>
                    <td >
                        <?php if( $checkout_meta["status"][0] == "Package Received" ) { ?>
                            <div class="row">
                                <div class="col-lg-6">
                                    <button class='5star_btn btn-sm btn btn-danger w-100 mb-3' data-action="item_not_avlb_in_package" data-post_id="<?php echo $post->ID; ?>">
                                        No
                                    </button>
                                </div>
                                <div class="col-lg-6">
                                    <button class='5star_btn btn-sm btn btn-success w-100 mb-3' data-action="item_avlb_in_package" data-post_id="<?php echo $post->ID; ?>">
                                        Yes
                                    </button>
                                </div>
                            </div>
                        <?php }  
                        elseif ( $checkout_meta["status"][0] == "Processing Order" ) { ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php if( in_array( $meta["status"][0], array("Received", "Graded")  )) { ?>
                                    <button class='5star_btn btn-sm btn btn-success w-100' data-action="set_grade" data-post_id="<?php echo $post->ID; ?>">
                                        Set Grade
                                    </button>
                                    <?php }  ?>
                                </div>
                            </div>
                        <?php } else {?>
                            -
                        <?php } ?>
                    </td>
                    <?php } ?>
                    <td><?php echo $post->ID; ?></td>
                    <td><?php echo $card["year"]; ?></td>
                    <td><?php echo $card["brand"]; ?></td>
                    <td><?php echo $card["card_number"]; ?><br><small><?php echo $card["attribute"]; ?></small></td>
                    <td><?php echo $card["player"]; ?></td>
                    <td class=".card_status"><?php echo $meta["status"][0]; ?></td>
                    <?php if( in_array( $checkout_meta["status"][0], $processed_status ) ){ ?>
                    <td class="grade text-end"><?php echo $meta["grade"][0]; ?></td>
                    <?php }?>
                    <td class='text-end'><?php echo "$" . number_format((float)$card["dv"], 2, '.', ''); ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float) $card_grading_charge, 2, '.', ''); ?></td>
                </tr>
                <?php          
                        }
                    } else {
                ?>
                <tr>
                    <td class="text-center" colspan="9">Empty</td>
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
    </div>
    
</div>


<div class="modal fade view_card" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Set Card Grade
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="view_card_form_box">
                    <div class="modal-body py-2 px-3">
                        <forn id="set_grade_form">

                            <input type="hidden" name="user_id" value='<?php echo $user_id; ?>'/>
                            <input type="hidden" name="grading" value=''/>
                            <input type="hidden" name="max_dv" value=''/>
                            <input type="hidden" name="post_id" value=''/>
                            <input type="hidden" name="card" value=''/>
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <label for="grade">Grade</label>
                                    <input id="grade_input" type="text" name="grade" style="font-size: 3em !important; text-align: center !important; color: white !important; background-color: black !important;"  value="" data-field_check="required"  class="form-control mb-2"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="year">Year</label>
                                    <input type="number" name="year" value="" data-field_check="required"  class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="dv">Declared Value</label>
                                    <input type="number" name="dv" value="" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="brand">Brand</label>
                                    <input type="text" name="brand" value="" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="card_number">Card Number</label>
                                    <input type="text" name="card_number" value="" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="player">Player Name</label>
                                    <input type="text" name="player" value="" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="attribute">Attribute S/N</label>
                                    <input type="text" name="attribute" value="" data-field_check="" class="form-control mb-2" disabled/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                        <button class="btn border btn-primary 5star_btn" data-action="confirm_card_grade">Save Grade</button>
                    </div>
                </div>
		</div>
	</div>
</div>

