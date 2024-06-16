<?php 

global $wpdb;

$consignment = $this->wpdb->get_results ( "
SELECT * 
FROM consignment
where user_id = " . get_current_user_id()

);

?>

<button class="btn btn-sm btn-success mb-3 ebayintegration-btn" data-action="show_log_consign_modal">
    Log Card
</button>
<button class="btn btn-sm btn-primary mb-3  ebayintegration-btn" data-action="show_ship_batch_modal">
    Ship Batch
</button>
<div class="table-responsive d-none d-lg-block">
    <table class="table table-sm table-bordered" id="new_consignment">
        <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th>Year</th>
                <th>Brand</th>
                <th>Player Name</th>
                <th class="text-end">Card Number</th>
                <th class="text-end">Attribute S/N</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if( count( $consignment ) == 0 ){
            ?>
            <tr class="empty_consignment">
                <td colspan="7" class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $consignment as $card ){

                        $data = json_decode( $card->data, true );

            ?>
            <tr class='consigned_item_row' data-id=''>
                <td>
                    <a class='text-dark consigned_item_view' data-id='' href='#'>
                        <i class='fa-solid fa-xl fa-bars'></i>
                    </a>
                </td>
                <td><?php echo $data["year"] ?></td>
                <td><?php echo $data["brand"] ?></td>
                <td></td>
                <td class='text-end'></td>
                <td class='text-end'></td>
            </tr>
            <?php 
                    }
                }
            ?>  
        </tbody>
    </table>
</div>
<div class="d-lg-none pb-2">
    <table class="table table-sm table-bordered" id="new_consignment_mobile">
        <thead>
            <tr>
                <th>Items</th>
            </tr>
        </thead>
        <tbody>
            <tr class="empty_consignment">
                <td class="text-center py-5">
                    Empty
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal fade log_consign_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Log Card to Consign
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body py-2 px-3">
                <div class="row formbox">
                    <div class="col-6">                        
                        <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">
                        
                        <label>Qty</label>
                        <input type="number" name="qty" class="form-control" value="3">
                    </div>
                    <div class="col-6">
                        <label>Year</label>
                        <input type="number" name="year" class="form-control" value="2000">
                    </div>
                    <div class="col-md-12">
                        <label>Brand</label>
                        <input type="text" name="brand" class="form-control p-1" value="brand">
                    </div>
                    <div class="col-md-12">
                        <label>Player Name</label>
                        <input type="text" name="player_name" class="form-control p-1" value="player1">
                    </div>
                    <div class="col-sm-6">
                        <label>Card Number</label>
                        <input type="text" name="card_number" class="form-control p-1" value="CN1100">
                    </div>
                    <div class="col-sm-6">
                        <label>Attribute S/N</label>
                        <input type="text" name="attribute_sn" class="form-control p-1" value="SN0011SS">
                    </div>
                </div>
                <div class="d-none p-5 text-center loading">

                    Adding card, please wait...

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>

                <button class="btn border btn-primary ebayintegration-btn" 
                    data-action='confirmAddConsign' 
                >
                    Log Card
                </button>
            </div>

		</div>
	</div>
</div>


<div class="modal fade ship_batch_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Ship Cards
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body py-2 px-3">
                <forn id="shipping_info_form">

                        <input type="hidden" name="user_id" value="1">
                        <input type="hidden" name="order_number" value="7834">
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
                    
                </forn>
            </div>
            <div class="modal-footer">
                <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>

                <button class="btn border btn-primary ebayintegration-btn" 
                    data-action='confirmAddConsign' 
                >
                    Card Shipped
                </button>
            </div>

		</div>
	</div>
</div>

