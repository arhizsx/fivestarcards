
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

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
            <a href="/my-account/grading" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/my-account/consignment" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/my-account/payout" class="5star_btn btn text-left btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-money-bill me-2"></i>
                Payout
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
            <?php 

                if( !isset($_GET["mode"])){

                $user_id = get_current_user_id();

                $skus = get_user_meta( $user_id, "sku", true );		
                $array = implode("','",$skus);
            
                $sql = "
                    SELECT * 
                    FROM  payouts
                    where user_id = " .  $user_id .  "
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
                                    <th class="text-center">Cards</th>
                                    <th class="text-start">Request Date</th>
                                    <th class="text-end">Amount Requested</th>
                                    <th class="text-end">Status</th>
                                    <th class="text-end">Payout Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if( count( $cards ) > 0 ){
                                        foreach( $cards as $card ){ 
                                            $data = json_decode($card->data, true);
                                ?>
                                <tr class="payment_request_row ebayintegration-btn" data-action="show_payment_request" data-request_id="<?php echo $card->id ?>">
                                    <td class="text-start"><?php echo $card->id + 1000 ?></td>
                                    <td class="text-center"><?php echo $data["cards_count"] ?></td>
                                    <td class="text-start"><?php echo $card->add_timestamp ?></td>
                                    <td class="text-end"><?php echo $data["requested_amount"] ?></td>
                                    <td class="text-end"><?php echo $card->status ?></td>
                                    <td class="text-end"></td>
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
                ?>
                    <form class="form" id="payout_request_form">
                        <input type="hidden" name="action" value="confirmPayoutRequest">
                        <input type="hidden" name="user_id" value="<?php echo get_current_user_id() ?>">
                        <div class="row">
                            <H5 style="color: black;">Cards Included</H5>
                            <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped table-sm table-hover search_table_paid">
                                    <thead>
                                        <tr>
                                            <th class="text-start" width="50%">Item</th>
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
                                                <div class="title text-start">
                                                    <a href="<?php echo $data["Item"]['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                                                        <?php print_r( $data["Item"]["Title"] ); ?>
                                                    </a>
                                                </div> 
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
                                                    $final = ($rate * $sold_price )  + $fees;
                                                }
                                                elseif( $sold_price >= 10 && $sold_price <= 49.99 ){
                                                    $rate = .82;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  + $fees;
                                                }
                                                elseif( $sold_price >= 50 && $sold_price <= 99.99 ){
                                                    $rate = .84;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  + $fees;
                                                }
                                                elseif( $sold_price >= 100 && $sold_price <= 199.99 ){
                                                    $rate = .85;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  + $fees;
                                                }
                                                elseif( $sold_price >= 200 && $sold_price <= 499.99 ){
                                                    $rate = .86;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  + $fees;
                                                }
                                                elseif( $sold_price >= 500 && $sold_price <= 999.99 ){
                                                    $rate = .87;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  + $fees;
                                                }
                                                elseif( $sold_price >= 1000 && $sold_price <= 2999.99 ){
                                                    $rate = .88;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  + $fees;
                                                }
                                                elseif( $sold_price >= 3000 && $sold_price <= 4999.99 ){
                                                    $rate = .90;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  + $fees;
                                                }
                                                elseif( $sold_price >= 5000 && $sold_price <= 8999.99 ){
                                                    $rate = .92;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  + $fees;
                                                }
                                                elseif( $sold_price >= 9000){
                                                    $rate = .93;
                                                    $fees = 0;
                                                    $final = ($rate * $sold_price )  + $fees;
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
                        <div class="row mb-3">
                            <div class="col-xl-4">
                                <label>Total Amount</label>
                                <input  class="form-control mb-3 px-2 pb-1 pt-2" disabled type="text" value="$<?php echo number_format(( $payout_total ), 2, '.', ',');?>">
                            </div>
                            <div class="col-xl-4">
                                <label>Cards Count</label>
                                <input  class="form-control mb-3 p-2 pb-1 pt-2" disabled type="text" value="<?php echo $available ?>">          
                            </div>
                            <div class="col-xl-4">
                                <label>Payment Method</label>
                                <select class="form-control mb-3" name="payment_method">
                                    <option value="Paypal">Paypal</option>
                                </select>
                            </div>
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