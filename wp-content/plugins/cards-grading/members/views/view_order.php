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
