<?php 
$current_user = wp_get_current_user();
?>
<style>
    .floating-button-container {
        position: fixed;
        right: 15px;
        bottom: 5px;
        width: 54px;
    }
    .floating-button {
        opacity: 100%;
        cursor: pointer;
        margin-bottom: 5px;
        padding: 0;
        border: 1px solid white;
        border-radius: 35px; height: 39px; width: 39px;
        line-height: 39px;
        box-shadow: -0.46875rem 0 2.1875rem rgb(4 9 20 / 3%), -0.9375rem 0 1.40625rem rgb(4 9 20 / 3%), -0.25rem 0 0.53125rem rgb(4 9 20 / 5%), -0.125rem 0 0.1875rem rgb(4 9 20 / 3%);
    }
    .floating-button:hover {
        opacity: 100%;
    }
    .floating-buttons-hide, .floating-buttons-show {
        height: 20px; margin-bottom:30px; margin-top: -15px; cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-12">
        <H1 style="color: black;">
            Hello, <?php echo $current_user->display_name; ?>
        </H1> 
    </div>
    <div class="col-12 text-start">
        <button id="float_btn_add_ticket" class="btn btn-xl btn-primary ebayintegration-btn"  data-action="add_new_order">
            <i class="fa fa-circle-plus me-2"></i> New Order
        </button>

        <?php 




            if( in_array( get_current_user_id(), array( 587, 1, 579, 1087, 698  ) )  ) {
        ?>
        <button id="float_btn_add_payout" class="btn btn-xl btn-success ebayintegration-btn"  data-action="add_new_payout">
            <i class="fa fa-money-bill me-2"></i> Request Payout
        </button>
        <?php 
            }
        ?>
    </div>
</div>

<div class="modal fade add_new_order_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title mb-0 p-0">
					New Order
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body p-3">
                <div class="row">
                    <div class="col">
                        <a class="btn border btn-primary form-control" href="/my-account/grading/new" >
                            Card Grading
                        </a>
                    </div>
                    <div class="col">
                        <a class="btn border btn-danger form-control" href="/my-account/consignment/new" >
                            Card Consignment
                        </a>
                    </div>
                </div>    


            </div>
		</div>
	</div>
</div>

<div class="modal fade add_new_payment_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal2" aria-hidden="true"  data-backdrop="" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog modal-xl" id="dxmodal2">
		<div class="modal-content modal-ajax" style="margin-bottom: 200px;">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title mb-0 p-0">
					New Payment Request
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body p-3">
                <?php 
                    if( $user_id == 1 ){
                ?>
                <div style="color: black;">Please wait we are brewing something cool...</div>
                <?php        
                    } else {


                        $user_id = get_current_user_id();

                        $skus = get_user_meta( $user_id, "sku", true );		
                        $array = implode("','",$skus);
                    
                        $sql = "
                            SELECT * 
                            FROM  ebay
                            where status = 'SoldListPaid' AND sku IN ('" . $array . "')
                                AND request_id IS NULL
                            ORDER BY id DESC
                        ";
                    
                        $cards = $this->wpdb->get_results ( $sql );
                        $available = count($cards);
                        $payout_total = 0;
                ?>
                    <form class="form" id="payout_request_form_request">
                        <input type="hidden" name="action" value="confirmPayoutRequest">
                        <input type="hidden" name="user_id" value="<?php echo get_current_user_id() ?>">
                        <div class="row">
                            <H5 style="color: black;">Cards Included</H5>
                            <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped table-sm table-hover search_table_paid">
                                    <thead>
                                        <tr>
                                            <th class="text-start" width="40%">Item</th>
                                            <th class="text-start">SKU</th>
                                            <th class="text-end">Price Sold</th>
                                            <th class="text-end">Rate</th>
                                            <th class="text-end">Fees</th>
                                            <th class="text-end">Final</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if( $available > 0 ){
                                            foreach($cards as $item){ 
                                                $ctr++;
                                                $data = json_decode($item->data, true);


                                        ?>
                                        <input type="hidden" name="card[<?php echo $ctr ?>]" value="<?php echo $item->item_id; ?>">
                                        <tr>
                                            <td class="text-start">
                                                    <a href="<?php echo $data["Item"]['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                                                        <?php print_r( $data["Item"]["Title"] ); ?>
                                                    </a>
                                            </td>
                                            <td class="text-start">
                                                <?php echo $item->sku ?>
                                            </td>
                                            <?php 
                                                $sold_price = (float) $data["TransactionPrice"];  
                                            ?>
                                            <td class="text-end">
                                                $<?php 
                                                echo number_format(( $sold_price), 2, '.', ',');
                                                ?>
                                            </td>
                                            <?php 
                                                if( $sold_price < 10 ){
                                                    $rate = 1;
                                                    $fees = 3;
                                                    $final = ($rate * $sold_price )  - $fees;
                                                }
                                                elseif( $sold_price >= 10 && $sold_price <= 49.99 ){
                                                    $rate = .82;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  - $fees;
                                                }
                                                elseif( $sold_price >= 50 && $sold_price <= 99.99 ){
                                                    $rate = .84;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  - $fees;
                                                }
                                                elseif( $sold_price >= 100 && $sold_price <= 199.99 ){
                                                    $rate = .85;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  - $fees;
                                                }
                                                elseif( $sold_price >= 200 && $sold_price <= 499.99 ){
                                                    $rate = .86;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  - $fees;
                                                }
                                                elseif( $sold_price >= 500 && $sold_price <= 999.99 ){
                                                    $rate = .87;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  - $fees;
                                                }
                                                elseif( $sold_price >= 1000 && $sold_price <= 2999.99 ){
                                                    $rate = .88;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  - $fees;
                                                }
                                                elseif( $sold_price >= 3000 && $sold_price <= 4999.99 ){
                                                    $rate = .90;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  - $fees;
                                                }
                                                elseif( $sold_price >= 5000 && $sold_price <= 8999.99 ){
                                                    $rate = .92;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  - $fees;
                                                }
                                                elseif( $sold_price >= 9000){
                                                    $rate = .93;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  - $fees;
                                                    echo $final;
                                                }

                                                $payout_total = $payout_total + $final;
                                            ?>
                                            <td class="text-end">
                                                <?php echo number_format(( $rate * 100), 2, '.', ','); ?>%                                            
                                            </td>
                                            <td class="text-end">
                                                $<?php 
                                                echo number_format(( $fees), 2, '.', ',');
                                                ?>
                                            </td>
                                            <td class="text-end">
                                                $<?php 
                                                echo number_format(( $final), 2, '.', ',');
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                            } 
                                        } 
                                        else {
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center p-5">
                                                No Items
                                            </td>
                                        </tr>
                                        <?php 
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-xl-4">
                                <label>Payment Method</label>
                                <select class="form-control mb-3 payment_method" name="payment_method">
                                    <option value="">Select Payment Method</option>
                                    <option value="Paypal">Paypal</option>
                                    <option value="ACH">ACH</option>
                                </select>
                            </div>
                            <div class="col-xl-4">
                                <label>Cards Count</label>
                                <input  class="form-control mb-3 p-2 pb-1 pt-2" disabled type="text" value="<?php echo $available ?>">          
                            </div>
                            <div class="col-xl-4">
                                <label>Total Amount</label>
                                <input  class="form-control mb-3 px-2 pb-1 pt-2" disabled type="text" value="<?php echo number_format(( $payout_total ), 2, '.', ',');?>">
                            </div>
                        </div>
                        <div class="row mb-3 d-none paypal">
                            <div class="col-xl-12">
                                <label>Paypal Email</label>
                                <input  class="form-control mb-3 p-2 pb-1 pt-2"  type="text" value="" name="paypal_email">          
                            </div>
                        </div>
                        <div class="row mb-3 d-none ach">
                            <div class="col-xl-6">
                                <label>Bank Name</label>
                                <input  class="form-control mb-3 p-2 pb-1 pt-2"  type="text" value="" name="bank_name">          
                            </div>
                            <div class="col-xl-6">
                                <label>Bank Routing Number</label>
                                <input  class="form-control mb-3 p-2 pb-1 pt-2"  type="text" value="" name="bank_routing_number">          
                            </div>
                            <div class="col-xl-6">
                                <label>Bank Account Number</label>
                                <input  class="form-control mb-3 p-2 pb-1 pt-2"  type="text" value="" name="bank_account_number">          
                            </div>
                            <div class="col-xl-6">
                                <label>Name on Bank Account</label>
                                <input  class="form-control mb-3 p-2 pb-1 pt-2"  type="text" value="" name="name_on_bank_account">          
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-xl-12">
                                <label>Remarks / Message</label>
                                <textarea class="form-control" name="remarks"></textarea>                            
                            </div>
                        </div>

                        <input type="hidden" value="<?php echo $payout_total ?>" name="requested_amount">
                        <input type="hidden" value="<?php echo $available ?>" name="cards_count">
                    </form>
                <?php 

                    }
                ?>
            </div>
            <div class="modal-footer">
                <button id="float_btn_add_payout" class="btn btn-xl btn-primary ebayintegration-btn"  data-action="confirmPayoutRequest">
                    <i class="fa fa-money-bill me-2"></i> Confirm Payout Request
                </button>
            </div>
		</div>
	</div>
</div>