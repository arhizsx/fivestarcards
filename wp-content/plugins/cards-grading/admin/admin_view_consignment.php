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

$admin_status = array( "Order Partial Payment", "Order Consigned" );
$admin_action_status = array( "Consigned" );

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
                    <div class='order-label'>Total Declared Value</div>
                    <div class='order-data'>$<?php echo number_format((float)$total_dv, 2, '.', ''); ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Total Cards</div>
                    <div class='order-data'><?php echo $cards_count; ?></div>
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
            </div>

        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <H3 style="color: black !important;">Cards List</H3>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 text-end">
            <?php 
            if( $checkout_meta["status"][0] == "Order Partial Payment" ) { 
            ?>
                <button class='5star_btn btn btn-danger mb-3' data-action="set_selling_price"  data-order_number="<?php echo $params['order_number'] ?>">
                    Close Order
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
                    <th>Action</th>
                    <th>ID</th>
                    <th width="30%">Card</th>
                    <th>Status</th>
                    <th class="text-end">DV</th>
                    <th class="text-end">Grading</th>
                    <th class="text-end">Sold Price</th>
                    <th class="text-end">To Receive</th>
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

                ?>
                <tr class="admin-card-row" data-post_id="<?php echo $post->ID; ?>" data-card='<?php echo json_encode($card) ?>' data-grade="<?php echo $meta["grade"][0]; ?>">
                    <?php if( in_array( $checkout_meta["status"][0], $admin_status ) ){ ?>
                    <td >
                        <?php if( $meta["status"][0] == "Consigned" ) { ?>
                            <button class='5star_btn btn-sm btn btn-success w-100 mb-3' data-action="card_sold" data-post_id="<?php echo $post->ID; ?>">
                                Card Sold
                            </button>
                        <?php } else {?>
                            -
                        <?php } ?>
                    </td>
                    <?php } ?>
                    <td>
                        <?php echo $post->ID; ?>
                    </td>
                    <td style="font-size: 12px !important;">
                        <div class="row">
                            <div class="col-4">Grade</div>
                            <div class="col">
                                <input type="text" class="form-control mb-2" value="<?php echo $meta["grade"][0]; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">Player</div>
                            <div class="col">
                                <input type="text" class="form-control mb-2" value="<?php echo $card["player"]; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">Year</div>
                            <div class="col">
                                <input type="text" class="form-control mb-2" value="<?php echo $card["year"]; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">Brand</div>
                            <div class="col">
                                <input type="text" class="form-control mb-2" value="<?php echo $card["brand"]; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">Card #</div>
                            <div class="col">
                                <input type="text" class="form-control mb-2" value="<?php echo $card["card_number"]; ?>">
                            </div>
                        </div>                        
                        <div class="row">
                            <div class="col-4">Attribute #</div>
                            <div class="col">
                                <input type="text" class="form-control mb-2" value="<?php echo $card["attribute"]; ?>">
                            </div>
                        </div>                        
                    </td>
                    <td class=".card_status"><?php echo $meta["status"][0]; ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float) $card["dv"], 2, '.', ''); ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float) $card_grading_charge, 2, '.', ''); ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float) 0, 2, '.', ''); ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float) 0, 2, '.', ''); ?></td>
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
					Set Card Selling Price
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
                                    <label for="sold_price">Selling Price</label>
                                    <input id="sold_price" type="number" name="sold_price" style="font-size: 3em !important; text-align: center !important; color: white !important; background-color: green !important;"  value="" data-field_check="required"  class="form-control mb-2"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <label for="grade">Grade</label>
                                    <input type="text" name="grade" value="" data-field_check="required"  class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <label for="year">Year</label>
                                    <input type="number" name="year" value="" data-field_check="required"  class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4">
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
                        <button class="btn border btn-primary 5star_btn" data-action="confirm_sold_price">Save Selling Price</button>
                    </div>
                </div>
		</div>
	</div>
</div>

