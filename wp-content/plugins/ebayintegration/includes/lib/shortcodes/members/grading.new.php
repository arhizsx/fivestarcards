<?php 

global $wpdb;

$consignment = $this->wpdb->get_results ( "
SELECT * 
FROM consignment
where user_id = " . get_current_user_id() . " 
and status = 'logged'
order by id desc
"
);

?>

<button class="btn btn-sm btn-success mb-3 ebayintegration-btn" data-action="show_log_consign_modal">
    Log Card
</button>
<button class="btn btn-sm btn-primary mb-3  ebayintegration-btn" data-action="show_ship_batch_modal">
    Ship Cards
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
            <tr class='consigned_item_row' data-id='<?php echo $card->id; ?>'>
                <td>
                    <a class='text-danger  ebayintegration-btn' data-action="removeConsignedCardRow"  data-id='<?php echo $card->id ?>' data-user_id="<?php echo get_current_user_id(); ?>" href='#'>
                        <i class='fa-solid fa-lg fa-xmark'></i>
                    </a>
                </td>
                <td><?php echo $data["year"] ?></td>
                <td><?php echo $data["brand"] ?></td>
                <td><?php echo $data["player_name"] ?></td>
                <td class='text-end'><?php echo $data["card_number"] ?></td>
                <td class='text-end'><?php echo $data["attribute_sn"] ?></td>
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
            <?php
                if( count( $consignment ) == 0 ){
            ?>
            <tr class="empty_consignment">
                <td class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $consignment as $card ){
            ?>
            <tr class='consigned_item_row' data-id='<?php echo $card->id; ?>'>
                <td>
                    <div class='w-100 p-0 text-end' style='position: relative;'>
                        <a class='text-danger ebayintegration-btn' data-action="removeConsignedCardRow" data-id='<?php echo $card->id ?>' data-user_id="<?php echo get_current_user_id(); ?>" href='#' style='position: absolute; right: 0px;'>
                            <i class='fa-solid fa-xl fa-xmark'></i>
                        </a>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Player</div>
                        <div class='col-sm-8'>
                            <?php echo $data["player_name"] ?>								
                        </div>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Year</div>
                        <div class='col-sm-8'>
                            <?php echo $data["year"] ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Brand</div>
                        <div class='col-sm-8'>
                            <?php echo $data["brand"] ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-4'>Card #</div>
                        <div class='col-sm-8'>
                            <?php echo $data["card_number"] ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Attribute SN</div>
                        <div class='col-sm-8'>
                            <?php echo $data["attribute_sn"] ?>
                        </div>
                    </div>
                </td>
            </tr>
            <?php
                    }
                }
            ?>
        </tbody>
    </table>
</div>

<div class="modal fade log_consign_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Log Card to Grade
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body py-2 px-3">
                <div class="row formbox">
                    <div class="col-12">
                        <label>Grading Type</label>
                        <select class="form-control">
                            <option value="psa-value_bulk" data-max_dv="499" data-per_card="19">PSA - Value Bulk</option>
                            <option value="psa-value_plus" data-max_dv="499" data-per_card="40">PSA - Value Plus</option>
                            <option value="psa-regular" data-max_dv="1499" data-per_card="75">PSA - Regular</option>
                            <option value="psa-express" data-max_dv="2499" data-per_card="165">PSA - Express</option>
                            <option value="psa-super_express" data-max_dv="4999" data-per_card="330">PSA - Super Express</option>
                        </select>
                    </div>
                    <div class="col-6">                        
                        <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">
                        
                        <label>Qty</label>
                        <input type="number" name="qty" class="form-control" value="">
                    </div>
                    <div class="col-6">
                        <label>Year</label>
                        <input type="number" name="year" class="form-control" value="">
                    </div>
                    <div class="col-md-12">
                        <label>Brand</label>
                        <input type="text" name="brand" class="form-control p-1" value="">
                    </div>
                    <div class="col-md-12">
                        <label>Player Name</label>
                        <input type="text" name="player_name" class="form-control p-1" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Card Number</label>
                        <input type="text" name="card_number" class="form-control p-1" value="">
                    </div>
                    <div class="col-sm-6">
                        <label>Attribute S/N</label>
                        <input type="text" name="attribute_sn" class="form-control p-1" value="">
                    </div>
                </div>
                <div class="d-none p-5 text-center loading">

                    Adding card, please wait...

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>

                <button class="btn border btn-success ebayintegration-btn" 
                    data-action='confirmAddConsign' 
                >
                    Log Card
                </button>
            </div>

		</div>
	</div>
</div>


<div class="modal fade ship_batch_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px">
	<div class="modal-dialog modal-lg" style="margin-bottom: 150px;">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Ship Cards
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body">
                <div class="formbox">

                    <div class="row">
                        <div class="col">
                            <H5 style="color: black;">Ship your items to</H5>
                        </div>
                    </div>
                    <div class="row border-bottom mb-3">
                        <div class="col-lg-6 small pb-3">
                            <div>USPS</div>
                            <div>Matt Sellers</div>
                            <div>PO Box 263 Hartland, WI 53029</div>
                        </div>
                        <div class="col-lg-6 small pb-3">
                            <div>FedEx / UPS / DHL</div>
                            <div>Matt Sellers</div>
                            <div>203 E Wisconsin Ave Suite 203C Oconomowoc, WI 53066</div>
                        </div>
                    </div>
                    
                    <forn id="shipping_info_form">

                            <input type="hidden" name="user_id" value="<?php echo get_current_user_id() ?>">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                                    <label for="carrier">Carrier</label>
                                    <select name="carrier" class="form-control" data-field_check="required">
                                        <option value="">Select Carrier</option>
                                        <option value="USPS">USPS</option>
                                        <option value="FedEx">FedEx</option>
                                        <option value="DHL">DHL</option>
                                        <option value="UPS">UPS</option>
                                    </select>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                                    <label for="shipped_by">Shipped By</label>
                                    <input type="text" name="shipped_by" class="form-control p-2" data-field_check="required">
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                                    <label for="tracking_number">Tracking Number</label>
                                    <input type="text" name="tracking_number" class="form-control p-2" data-field_check="required">
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 mb-3">
                                    <label for="shipping_date">Shipping Date</label>
                                    <input type="date" name="shipping_date" class="form-control p-2" data-field_check="required">
                                </div>
                            </div>
                    </forn>
                </div>
                <div class="d-none p-5 text-center loading">

                    Processing cards, please wait...

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>

                <button class="btn border btn-primary ebayintegration-btn" 
                    data-action='confirmConsignCardsShipping' 
                >
                    Card Shipped
                </button>                    
            </div>
		</div>
	</div>
</div>

