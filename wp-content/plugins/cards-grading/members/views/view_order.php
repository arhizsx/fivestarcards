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

    if( $meta["status"][0] != 'Not Available' ){

    $card_total_dv = $card["dv"] * $card["quantity"];

    $total_dv = $total_dv + $card_total_dv;
    $cards_count = $cards_count + $card["quantity"];

    }
}

$processed_status = array("Completed - Grades Ready");

$consignment_status = array("Order Partial Consignment", "Order Consigned", "Ready For Payment", "Consignment Paid");

$grading_orders_id = $checkout_meta["grading_orders_id"][0];

$sql = "SELECT * FROM grading where order_id='". $grading_orders_id . "' AND type LIKE '%_file'";
$grading_files = $this->wpdb->get_results ( $sql );	

?>
<div class="m-0 p-0">
    <div class="row">
        <div class="col mb-4">
            <a href="/my-account/grading/?mode=<?php echo $_GET["mode"] ?>">Back to My Orders</a>
        </div>
    </div>

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
                if( $checkout_meta["status"][0] == "Completed - Grades Ready" ) { 

                    if( $posts )
                    {
                        $pay_grading = 0;
                        $consign_card = 0;
                        $not_available = 0;

                        foreach($posts as $post)
                        {
                            $meta = get_post_meta($post->ID);
                            if( $meta["status"][0] == "Consign Card" ){
                                $consign_card++;
                            }
                            elseif( $meta["status"][0] == "Pay Grading" ){
                                $pay_grading++;
                            }
                            elseif( $meta["status"][0] == "Not Available" ){
                                $not_available++;
                            }
                        }

                        if( $pay_grading + $consign_card + $not_available == count($posts) ){
                            $show_btn = "";
                        } else {
                            $show_btn = "d-none";
                        }
                    }
            ?>
                <button class='5star_btn btn btn-primary <?php echo $show_btn; ?>' data-action="complete_grading_process" data-order_number="<?php echo $params['order_number'] ?>">
                    Complete Grading Process
                </button>      
            <?php 
                } 

                elseif( $checkout_meta["status"][0] == "Incomplete Items Shipped" ) { 
            ?>
                <button class='5star_btn btn btn-primary' data-action="acknowledge_missing_cards" data-order_number="<?php echo $params['order_number'] ?>">
                    Acknowledge Missing Cards
                </button>      
            <?php 
                }

                elseif( $checkout_meta["status"][0] == "Order To Pay" ) { 
            ?>
                <button class='5star_btn btn btn-primary' data-action="order_paid" data-order_number="<?php echo $params['order_number'] ?>">
                    Order Paid
                </button>      
            <?php 
                }
                elseif( $checkout_meta["status"][0] == "To Ship" ) { 
            ?>

                <button class='5star_btn btn btn-primary' data-action="shipped">
                    Items Shipped
                </button>      
            <?php 
                }
            ?>
            <button class='5star_btn btn btn-secondary' data-action="view_pdf"  data-order_number="<?php echo $params['order_number'] ?>">
                PDF
            </button>      
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
                <?php if( in_array( $checkout_meta["status"][0], $consignment_status ) ){ ?>
                    <th class='text-end'>Sold Price</th>
                    <th class='text-end'>To Receive</th>
                <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if( $posts ){

                        foreach($posts as $post)
                        {
                            $meta = get_post_meta($post->ID);
                            $card = json_decode($meta['card'][0], true);


                            if( $meta["status"][0] != 'Not Available' ){

                                $card_total_dv = $card["dv"] * $card["quantity"];
                                $card_grading_charge = $card["per_card"] * $card["quantity"];

                                if( in_array( $meta["status"][0], array("Pay Grading", "To Pay - Grade Only", "To Ship", "Shipped", "Received", "Paid - Grade Only", "Graded") ) ){
                                    $grading_charge = $grading_charge + $card_grading_charge;
                                }
        
                            }

                            $total_to_receive = $total_to_receive + $meta["to_receive"][0];

                            

                            $sql = "SELECT * FROM grading WHERE id = " . $card["db_id"];
                            $db_row = $this->wpdb->get_results ( $sql );
        
                            $db_row_data = json_decode($db_row[0]->data, true);
        
                ?>
                <tr class="user-card-row" data-post_id="<?php echo $post->ID; ?>" data-card='<?php echo json_encode($card) ?>'>

                    <?php 
                    $graded_count = 0;
                    $empty_cols = 9;
                    $add_col_one = 0;
                    $add_col_two = 0;
                    $add_col_three = 0;

                    if( array_key_exists( "title", $db_row_data ) == false && array_key_exists( "certImgFront", $db_row_data ) == false && array_key_exists( "certImgBack", $db_row_data ) == false  ){

                        $graded_count++;                 
                    ?>

                        <?php if( in_array( $checkout_meta["status"][0], $processed_status ) ){ 
                            $add_col_one = 1;
                        ?>
                        <td>                        
                            <?php 
                                if( $checkout_meta["status"][0] == "Completed - Grades Ready" ) { 
                                    if( in_array( $meta["status"][0], array("Graded", "Consign Card", "Pay Grading") ) ) {
                            ?>
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
                            <?php
                                    } 
                                } 
                            ?>
                        </td>
                        <?php } ?>
                        <td><?php echo $card["year"]; ?></td>
                        <td><?php echo $card["brand"]; ?></td>
                        <td><?php echo $card["card_number"]; ?><br><small><?php echo $card["attribute"]; ?></small></td>
                        <td><?php echo $card["player"]; ?></td>
                        <td><?php echo $meta["status"][0]; ?></td>
                        <?php if( in_array( $checkout_meta["status"][0], $processed_status ) ){ 
                            $add_col_two = 1;
                        ?>
                        <td class="text-end"><?php echo $meta["grade"][0];  ?></td>
                        <?php } ?>
                        <td class='text-end'><?php echo "$" . number_format((float)$card["dv"], 2, '.', ''); ?></td>
                        <td class='text-end'><?php echo "$" . number_format((float) $card_grading_charge, 2, '.', ''); ?></td>
                        <?php if( in_array( $checkout_meta["status"][0], $consignment_status ) ){ 
                            $add_col_three = 2;
                        ?>
                        <td class='text-end'><?php echo "$" . number_format((float) $meta["sold_price"][0], 2, '.', ''); ?></td>
                        <td class='text-end'><?php echo "$" . number_format((float) $meta["to_receive"][0], 2, '.', ''); ?></td>
                        <?php } ?>
                    <?php 
                    } 
                    

                    ?>
                </tr>
                <?php          
                        }

                    if($graded_count == 0){
                    ?>
                        <td colspan="<?php echo $empty_cols + $add_col_one + $add_col_two + $add_col_three  ?>" class="text-center p-3">Empty</td>
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

        <H3 style="color: black !important;">Graded Cards</H3>
        <table id="completed_graded" class="table table-bordered table-striped">
            <thead>
                <th>ID</th>
                <th width="50%">Title</th>
                <th>Photo</th>
                <th>Grade</th>
                <th>Certificate #</th>
            </thead>
            <tbody>
                <?php 
                    if( $posts ){

                        foreach($posts as $post)
                        {
                            $meta = get_post_meta($post->ID);
                            $card = json_decode($meta['card'][0], true);

                            $sql = "SELECT * FROM grading WHERE id = " . $card["db_id"];
                            $db_row = $this->wpdb->get_results ( $sql );
        
                            $db_row_data = json_decode($db_row[0]->data, true);
        
                            if( array_key_exists( "title", $db_row_data ) && array_key_exists( "certImgFront", $db_row_data ) && array_key_exists( "certImgBack", $db_row_data )  ){
        
                ?>
                <tr class='admin-graded-row' data-post_id='" + post_id + "'>
                    <?php if( in_array( $checkout_meta["status"][0], $processed_status ) ){ ?>
                        <th>Action</th>
                    <?php } ?>
                    <td><?php echo $post->ID ?></td>
                    <td><?php echo $db_row_data["title"] ?></td>
                    <td><a href='<?php echo $db_row_data["certImgFront"] ?>'><img width='100px' src='<?php echo $db_row_data["certImgFront"] ?>' target='_blank'></a><a href='<?php echo $db_row_data["certImgBack"] ?>' target='_blank'><img width='100px' src='<?php echo $db_row_data["certImgBack"] ?>'></a></td>
                    <td><?php echo $db_row_data["grade"] ?></td>
                    <td><?php echo $db_row_data["certificate_number"] ?></td>
                </tr>

                <?php 
                            }

                        }

                    }
                ?>


            </tbody>
        </table>

    </div>

    <div class='5star_btn_box_bottom w-100'>
        <?php if( in_array($checkout_meta["status"][0], $consignment_status )  == false ) { ?>

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
                <div class="row mb-2">
                    <div class="col text-end">
                        Grading Charge    
                    </div>
                    <div class="col text-end"  id="grading_charges">
                        $<?php echo number_format((float)$grading_charge, 2, '.', ''); ?>
                    </div>
                </div>
                <?php 
                    if( $checkout_meta["inspection"][0] == "1" ) { 
                ?>
                <div class="row">
                    <div class="col text-end">
                        Inspection Charge    
                    </div>
                    <div class="col text-end"  id="grading_charges">
                        $<?php echo number_format((float) $cards_count * 3, 2, '.', ''); ?>
                    </div>
                </div>
                <?php 
                    }
                ?>
            </div>
        </div>

        <?php } else { ?>

        <div class="row">
            <div class="col-lg-6 text-end pb-2 fw-bold cards_dv_total">
            </div>
            <div class="col-lg-6 text-end pb-2 fw-bold cards_charge_total">
                <div class="row mb-2">
                    <div class="col text-end">
                        Total To Receive
                    </div>
                    <div class="col text-end" id="total_dv">
                        $<?php echo number_format((float) $total_to_receive, 2, '.', ''); ?>
                    </div>
                </div>
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col text-end">
                       <span style="color: red;">LESS</span> Unpaid Grading Charge    
                    </div>
                    <div class="col text-end"  id="grading_charges">
                        $<?php echo number_format((float)$grading_charge, 2, '.', ''); ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col text-end">
                       To Receive 
                    </div>
                    <div class="col text-end"  id="grading_charges">
                        $<?php echo number_format((float) $total_to_receive - $grading_charge, 2, '.', ''); ?>
                    </div>
                </div>
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
                <div>Matt Sellers</div>
                <div>203 E Wisconsin Ave</div>
                <div>Suite 203C</div>
                <div>Oconomowoc, WI 53066</div>
            </div>
            <div class="col-lg-4">

            </div>
        </div>
        <?php } ?>
    </div>

    <?php
    if( count($grading_files) ){
    ?>
    <div class="row mt-3">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <H3 style="color: black !important;">Uploaded Cards List File</H3>
        </div>
    </div>
    <div class="table-responsive">   
        <table class='table table-sm table-bordered table-striped'>
            <thead>
                <tr>
                    <th>
                        File Details
                    </th>
                    <th class="text-end">
                        Quantity
                    </th>
                    <th class="text-end">
                        Card Show
                    </th>
                </tr>
            </thead>
            <tbody> 
                <?php 
                foreach($grading_files as $gfile){
                    $files = json_decode($gfile->data, true);
                        foreach($files as $file){
                ?>
                <tr>
                    
                    <td class=""><a target="_blank" href="<?php print_r( $file["baseurl"] ) ?>"><?php print_r( $file["name"] ) ?></a></td>
                    <td class="text-end"><?php print_r( $file["qty"] ) ?></td>
                    <td class="text-end"><?php print_r( $file["card_show"] ) ?></td>
                </tr>
                <?php 

                        }
                }
                ?>
            </tbody>
        </table>
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


<div class="modal fade paidmodal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Payment Information
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
                                <label for="mode_of_payment">Mode of Payment</label>
                                <select name="mode_of_payment" class="form-control" data-field_check="required">
                                    <option value="">Select Mode of Payment</option>
                                    <option value="Paypal">Paypal</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                                <label for="paid_by">Paid By</label>
                                <input type="text" name="paid_by" class="form-control" data-field_check="required">
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                                <label for="payment_date">Payment Date</label>
                                <input type="date" name="payment_date" class="form-control" data-field_check="required">
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                                <label for="amount_paid">Amount</label>
                                <input type="number" name="paamount_paidid_by" class="form-control" data-field_check="required">
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-3">
                                <label for="reference_number">Reference Number</label>
                                <input type="text" name="reference_number" class="form-control" data-field_check="required">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                    <button class="btn border btn-success 5star_btn" data-action='confirm_payment_info' data-order_number="<?php echo $params['order_number']; ?>" data-type=''>Submit Payment Details</button>
                </div>
            </div>
		</div>
	</div>
</div>
