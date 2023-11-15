<?php

$user_id = get_current_user_id();

$checkout_post = get_post($params['order_number']);
$checkout_meta = get_post_meta($checkout_post->ID);

if( $checkout_meta["user_id"][0] != $user_id){
    echo "<div class='p-5 text-center'>Not Allowed</div>";
    die;
}

$args = array(
    'meta_query' => array(
        'relations' =>  'AND',    
        array(
            'key' => 'checkout_id',
            'value' => $params['order_number']
        ),
        array(
            'key' => 'user_id',
            'value' => $user_id
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

$processed_status = array("Cards Graded");

?>

<div class="m-0 p-0">
    <div class="row">
        <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12" >
            <div class="order-label"><?php echo $params['title'] ?></div>
            <H1 style="color: black !important;"><?php echo $params['order_number'] ?></H1>
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
            <H4 style="color: black !important;">Cards List</H4>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 text-end">
            <?php 
                if( $checkout_meta["status"][0] == "Cards Graded" ) { 

                    if( $posts )
                    {
                        $pay_grading = 0;
                        $consign_card = 0;

                        foreach($posts as $post)
                        {
                            $meta = get_post_meta($post->ID);
                            if( $meta["status"][0] == "Consign Card" ){
                                $consign_card++;
                            }
                            elseif( $meta["status"][0] == "Pay Grading" ){
                                $pay_grading++;
                            }
                        }

                        if( $pay_grading + $consign_card == count($posts) ){
                            $show_btn = "";
                        } else {
                            $show_btn = "d-none";
                        }
                    }
            ?>
                <button class='5star_btn btn btn-primary mb-3 <?php echo $show_btn; ?>' data-action="complete_grading_process" data-order_number="<?php echo $params['order_number'] ?>">
                    Complete Grading Process
                </button>      
            <?php 
                } 

                elseif( $checkout_meta["status"][0] == "Incomplete Items Shipped" ) { 
            ?>
                <button class='5star_btn btn btn-primary mb-3' data-action="complete_grading_process" data-order_number="<?php echo $params['order_number'] ?>">
                    Acknowledge Missing Cards
                </button>      
            <?php 
                }
            ?>

Incomplete Items Shipped
        </div>
    </div>
    <div class="table-responsive mt-3">    
        <table class='table 5star_logged_cards table-bordered table-striped' data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/order-action") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
            <thead>
                <tr>
                <?php if( in_array( $checkout_meta["status"][0], $processed_status ) ){ ?>
                    <th>Action</th>
                <?php } ?>
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

                            $grading_charge = $grading_charge + $card_grading_charge;

                ?>
                <tr class="user-card-row" data-post_id="<?php echo $post->ID; ?>" data-card='<?php echo json_encode($card) ?>'>
                    <?php if( in_array( $checkout_meta["status"][0], $processed_status ) ){ ?>
                    <td>                        
                        <?php if( $checkout_meta["status"][0] == "Cards Graded" ) { ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class='5star_btn btn-sm btn btn-success w-100 mb-3' data-action="consign_card" data-post_id="<?php echo $post->ID; ?>">
                                        Consign
                                    </button>
                                </div>
                                <div class="col-sm-12">
                                    <button class='5star_btn btn-sm btn btn-primary w-100 mb-3' data-action="pay_card_grading" data-post_id="<?php echo $post->ID; ?>">
                                        Pay
                                    </button>
                                </div>
                            </div>
                        <?php } ?>
                    </td>
                    <?php } ?>
                    <td><?php echo $card["year"]; ?></td>
                    <td><?php echo $card["brand"]; ?></td>
                    <td><?php echo $card["card_number"]; ?><br><small><?php echo $card["attribute"]; ?></small></td>
                    <td><?php echo $card["player"]; ?></td>
                    <td><?php echo $meta["status"][0]; ?></td>
                    <?php if( in_array( $checkout_meta["status"][0], $processed_status ) ){ ?>
                    <td class="text-end"><?php echo $meta["grade"][0];  ?></td>
                    <?php } ?>
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
        <?php if( $posts ){ ?>
        <div class="row bottom_buttons">
            <div class="col-lg-12 text-end border-top pt-2 ">
                <?php if( $checkout_meta["status"][0] == "To Ship" ) { ?>
                <button class='5star_btn btn btn-primary' data-action="shipped">
                    Items Shipped
                </button>      
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php if( $checkout_meta["status"][0] == "To Ship" ) { ?>
        <div class="row mx-5">
            <div class="col-lg-12">
                <H3 style="color: black !important;">Ship Your Items To</H3>
            </div>
            <div class="col-lg-4 mb-3">
                <div><strong>USPS</strong></div>
                <div>Matt Sellers</div>
                <div>PO Box 263</div>
                <div>Hartland, WI 53029</div>
            </div>
            <div class="col-lg-4 mb-3">
                <div><strong>FedEx / UPS / DHL</strong></div>
                <div>PMB 394</div>
                <div>1256 Capitol Drive - Suite 700</div>
                <div>Pewaukee, WI 53072</div>
            </div>
            <div class="col-lg-4">

            </div>
        </div>
        <?php } ?>
    </div>
    
</div>


<div class="modal fade dxmodal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Set Shipping Information
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="" id="set_shipping_info_box">
                <div class="modal-body py-2 px-3">
                    <forn id="shipping_info_form">

                        <input type="hidden" name="user_id" value='<?php echo $checkout_meta["user_id"][0]; ?>'/>
                        <input type="hidden" name="order_number" value='<?php echo $params['order_number']; ?>'/>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                                <label for="carrier">Carrier</label>
                                <select name="carrier" class="form-control" data-field_check="required">
                                    <option value="">Select Carrier</option>
                                    <option value="USPS">USPS</option>
                                    <option value="FedEx">FedEx</option>
                                    <option value="DHL">DHL</option>
                                    <option value="UPS">UPS</option>
                                </select>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                                <label for="shipped_by">Shipped By</label>
                                <input type="text" name="shipped_by" class="form-control" data-field_check="required">
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                                <label for="tracking_number">Tracking Number</label>
                                <input type="text" name="tracking_number" class="form-control" data-field_check="required">
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                                <label for="shipping_date">Shipping Date</label>
                                <input type="date" name="shipping_date" class="form-control" data-field_check="required">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                    <button class="btn border btn-success 5star_btn" data-action='confirm_shipping' data-type=''>Set Shipping Details</button>
                </div>
            </div>
		</div>
	</div>
</div>
