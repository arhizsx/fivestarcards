
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>

<style>
    h3 {
        margin-bottom: 0px;
        color: black;

    }
    .payment_request_row {
        cursor: pointer;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <a href="/administrator" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-list me-2"></i>
                Dashboard
            </a>
            <a href="/administrator/grading" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/administrator/consignment" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/administrator/payouts" class="5star_btn btn text-left btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
            <i class="fa-solid fa-money-bill me-2"></i>
            Payouts
            </a>
            <a href="/administrator/members" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-users me-2"></i>
                Members
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
            <?php 

                if( !isset($_GET["mode"])){
            
                $sql = "
                    SELECT * 
                    FROM  payouts
                    ORDER BY id DESC
                ";
            
                $cards = $this->wpdb->get_results ( $sql );
            ?>
                <ul class="clearfix d-none d-lg-block">
                    <li class="active">
                        <a class="" href="/my-account/payout/">Payout Requests</a>
                    </li>
                </ul>
                <div class="content p-3 text-center">
                    <div class="table-responsive">
                        <table class="table table-border table-striped table-sm table-hover search_table_paid">
                            <thead>
                                <tr>
                                    <th class="text-start">Payment ID</th>
                                    <th class="text-start">Client</th>
                                    <th class="text-center">Cards</th>
                                    <th class="text-start">Request Date</th>
                                    <th class="text-end">Amount Requested</th>
                                    <th class="text-end">Paid Date</th>
                                    <th class="text-end">Status</th>
                                    <!-- <th class="text-end">Payout Date</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if( count( $cards ) > 0 ){
                                        foreach( $cards as $card ){ 

                                            $data = json_decode($card->data, true);

                                            $user_data = get_userdata($card->user_id);

                                            $user = [
                                                "id" => $user_data->data->ID,
                                                "name" => $user_data->data->display_name,
                                                "email" => $user_data->data->user_email,
                                            ];
                                    
                                ?>
                                <tr class="payment_request_row ebayintegration-btn" data-action="show_payment_request" data-payout_id="<?php echo $card->id ?>">
                                    <td class="text-start"><?php echo $card->id + 1000 ?></td>
                                    <td class="text-start"><?php echo $user["name"]; ?></td>
                                    <td class="text-center"><?php echo $data["cards_count"] ?></td>
                                    <td class="text-start"><?php echo $card->add_timestamp ?></td>
                                    <?php 
                                        if($card->update_timestamp == $card->add_timestamp){
                                            $tt = "";
                                        } else {
                                            $tt = $card->update_timestamp;
                                        }
                                    ?>
                                    <td class="text-end">$<?php echo number_format(( $data["requested_amount"] ), 2, '.', ',') ?></td>
                                    <td class="text-end"><?php echo   $tt ?></td>
                                    <td class="text-end"><?php echo $card->status ?></td>
                                    <!-- <td class="text-end"></td> -->
                                </tr>  
                                <?php
                                        } 
                                    } else {
                                ?>
                                <tr class="payment_request_row_empty">
                                    <td colspan="8">Empty</td>
                                </tr>
                                <?php
                                    }
                                ?>                                  
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php

                } else {
            ?>


            <?php 
                }
            ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show_payment_request_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal2" aria-hidden="true"  data-backdrop="" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog modal-xl" id="dxmodal2">
		<div class="modal-content modal-ajax" style="margin-bottom: 200px;">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title mb-0 p-0">
					Payment Request Details
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body p-3">
                <form class="form" id="payout_request_form_modal">
                    <input type="hidden" name="action" value="confirmPayoutDone">
                    <input type="hidden" name="user_id" value="">
                    <div class="row mb-3">
                        <input name="user_id"  class="form-control mb-3 p-2 pb-1 pt-2" disabled type="hidden" value="">          
                        <div class="col-xl-8">
                            <label>Client</label>
                            <input name="user_name"  class="form-control mb-3 p-2 pb-1 pt-2" disabled type="text" value="">          
                        </div>
                        <div class="col-xl-4">
                            <label>Email</label>
                            <input name="user_email"  class="form-control mb-3 p-2 pb-1 pt-2" disabled type="text" value="">          
                        </div>
                        <div class="col-xl-4">
                            <label>Payout ID</label>
                            <input name="payout_id"  class="form-control mb-3 p-2 pb-1 pt-2" type="hidden" value="">          
                            <input name="payout_id_full"  class="form-control mb-3 p-2 pb-1 pt-2" disabled type="text" value="">          
                        </div>
                        <div class="col-xl-4">
                            <label>Request Date</label>
                            <input name="request_date"  class="form-control mb-3 p-2 pb-1 pt-2" disabled type="text" value="">          
                        </div>
                        <div class="col-xl-4">
                            <label>Payout Date</label>
                            <input name="payout_date"  class="form-control mb-3 p-2 pb-1 pt-2" disabled type="text" value="">          
                        </div>
                    </div>
                    <div class="row">
                        <H5 style="color: black;">Cards Included</H5>
                        <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped table-sm table-hover search_table_paid"  id="payout_cards_table">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-xl-4">
                            <label>Cards Count</label>
                            <input name="cards_count" class="form-control mb-3 p-2 pb-1 pt-2" type="text" value="">          
                        </div>
                        <div class="col-xl-4">
                            <label>Payment Method</label>
                            <select class="form-control mb-3" name="payment_method">
                                <option value="Paypal">Paypal</option>
                            </select>
                        </div>
                        <div class="col-xl-4">
                            <label>Total Amount</label>
                            <input name="total_amount"  class="form-control mb-3 px-2 pb-1 pt-2" type="text" value="">
                        </div>
                        <div class="col-xl-12">
                            <label>Remarks / Message</label>
                            <textarea class="form-control" name="remarks"></textarea>                            
                        </div>
                    </div>
                    <input type="hidden" value="" name="requested_amount">
                    <input type="hidden" value="" name="cards_count">
                </form>
            </div>
            <div class="modal-footer">
                <button id="float_btn_add_payout"  data-bs-dismiss="modal"  class="btn btn-xl btn-secondary">
                    Cancel
                </button>                
                <button id="float_btn_done_payout" class="d-none btn btn-xl btn-primary ebayintegration-btn"  data-action="confirmPayoutDone">
                    <i class="fa fa-check me-2"></i> Done
                </button>
            </div>
		</div>
	</div>
</div>