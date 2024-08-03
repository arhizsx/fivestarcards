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
        <button id="float_btn_add_payout" class="btn btn-xl btn-primary ebayintegration-btn"  data-action="add_new_payout">
            <i class="fa fa-money-bill me-2"></i> Request Payout
        </button>
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
	<div class="modal-dialog modal-lg" id="dxmodal2">
		<div class="modal-content modal-ajax">
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
                            ORDER BY id DESC
                        ";
                    
                        $cards = $this->wpdb->get_results ( $sql );
                        $available = count($cards);
                        $payout_total = 0;

                        if( $available > 0 ){
                            foreach($cards as $item){ 
                                $ctr++;
                                $data = json_decode($item->data, true);
                                $payout_total = $$payout_total + $data["TransactionPrice"];
                            }
                        }                    
                ?>
                    <div class="row mb-3">
                        <div class="col-xl-4">
                            <label>Total Amount</label>
                            <input  class="form-control px-2 pb-1 pt-2" disabled type="text" value="$<?php echo number_format(( $payout_total ), 2, '.', ',');?>">
                        </div>
                        <div class="col-xl-4">
                            <label>Cards Count</label>
                            <input  class="form-control p-2 pb-1 pt-2" disabled type="text" value="<?php echo $available ?>">          
                        </div>
                        <div class="col-xl-4">
                            <label>Payment Method</label>
                            <select class="form-control" name="payment_method">
                                <option value="">Select Payment Method</option>
                                <option value="Paypal">Paypal</option>
                            </select>
                        </div>
                        <div class="col-xl-12">
                            <label>Remarks / Message</label>
                            <textarea class="form-control" name="remarks"></textarea>                            
                        </div>
                    </div>
                    <div class="row mb-3">
                        <H4 style="color: black;">Cards Included</H4>
                        <div class="overflow: auto">
                        <table class="table table-sm table-border table-striped table-sm table-hover search_table_paid">
                                <thead>
                                    <tr>
                                        <th class="text-start" width="60%">Item</th>
                                        <th class="text-end">eBay Pay Date</th>
                                        <th class="text-end">Price Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if( $available > 0 ){
                                        foreach($cards as $item){ 
                                            $ctr++;
                                            $data = json_decode($item->data, true);
                                    ?>
                                    <tr>
                                        <td class="text-start">
                                            <div class="title text-start">
                                                <strong><?php echo $ctr;  ?></strong>&nbsp;
                                                <a href="<?php echo $data["Item"]['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                                                    <?php print_r( $data["Item"]["Title"] ); ?>
                                                </a>
                                            </div> 
                                            <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                                            <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                                            <?php 
                                                $listing = $data["Item"]["ListingType"] == "Chinese" ? "Auction" : $data["Item"]["ListingType"]; 
                                            ?>
                                            <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                                        
                                        </td>
                                        <td class="text-end">
                                            <?php
                                            $paid_time = explode("T",$data["PaidTime"]); 
                                            echo $paid_time[0];
                                            ?>
                                        </td>
                                        <td class="text-end">
                                            $<?php 
                                            echo number_format(( $data["TransactionPrice"]), 2, '.', ',');
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                            $payout_total = $$payout_total + $data["TransactionPrice"];
                                        } 
                                    } 
                                    else {
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-center p-5">
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

                <?php 
                    }
                ?>
            </div>
            <div class="modal-footer p-3">
                    request
            </div>
		</div>
	</div>
</div>