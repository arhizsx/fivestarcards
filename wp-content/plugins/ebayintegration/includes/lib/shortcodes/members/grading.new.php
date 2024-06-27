<style>
    h3 {
        color: black;
    }
    .grading_box {
        border: 1px solid black;
        padding: 0px;
    }
    .grading_box table {
        margin: 0px !important;
    }
    .grading_title {
        height: 100px;
        vertical-align: middle;
        font-size: 1.2em;
        font-weight: bold;
    }
    .pricing {
        font-size: 3em !important;
        font-weight: bolder;
    }
</style>

<?php 


    if( isset( $_GET["type"] ) ){

        global $wpdb;

        $consignment = $this->wpdb->get_results ( "
        SELECT * 
        FROM consignment
        where user_id = " . get_current_user_id() . " 
        and status = 'logged'
        order by id desc
        "
        );        

        switch( $_GET["type"] ){

            case "psa-value_bulk": 
                $grading_title = "PSA Value Bulk";
                $max_dv = 499;
                $per_card = 19;
                break;

            case "psa-value_plus": 
                $grading_title = "PSA Value Plus";
                $max_dv = 499;
                $per_card = 40;
                break;

            case "psa-regular": 
                $grading_title = "PSA Regular";
                $max_dv = 1499;
                $per_card = 75;
                break;

            case "psa-express": 
                $grading_title = "PSA Express";
                $max_dv = 2499;
                $per_card = 165;
                break;

            case "psa-super_express": 
                $grading_title = "PSA Super Express";
                $max_dv = 4999;
                $per_card = 330;
                break;

            default: 
                $max_dv = 0;
                $per_card = 0;
            
        }
?>

<!-- Grading Title -->
<div class="">
    <H3><?php echo $grading_title; ?></H3>
</div>

<!-- Buttons -->
<div>
    <a href="/my-account/grading/" class="btn btn-sm btn-secondary mb-3 ">Back to Grading Types</a>

    <button class="btn btn-sm btn-success mb-3 ebayintegration-btn" data-action="show_log_consign_modal">
        Log Card
    </button>

    <button class="btn btn-sm btn-primary mb-3  ebayintegration-btn" data-action="show_ship_batch_modal">
        Ship Cards
    </button>
</div>

<!-- DESKTOP VIEW -->
<div class="table-responsive d-none d-lg-block">
    <table class="table table-sm table-bordered" id="new_consignment">
        <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th>Year</th>
                <th>Brand</th>
                <th>Player Name</th>
                <th>Card Number</th>
                <th class="text-end">DV</th>
                <th class="text-end">Grading</th>
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
                <td><?php echo $data["card_number"] ?><br><?php echo $data["attribute_sn"] ?></td>
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

<!-- MOBILE VIEW -->
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
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Declared Value</div>
                        <div class='col-sm-8'>
                            
                        </div>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Grading</div>
                        <div class='col-sm-8'>
                            
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

<!-- LOG MODAL -->
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

<!-- SHIP MODAL -->
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

<?php
    } else {
?>

<div class="row mx-3">
    <div class="col-lg col-md-4 text-center grading_box">
        <table class="table table-bordered">
            <tr>
                <td class='grading_title' style="background-color: #1ba01d; color: #ffffff;">Value Bulk</td>
            </tr>
            <tr>
                <td class="">
                    <div class="pricing">19</div>
                    <div>per card</div>
                </td>
            </tr>
            <tr>
                <td>DV < $499</td>
            </tr>
            <tr>
                <td>
                <a href="?type=psa-value_bulk" class="btn btn-sm btn-outline-primary">Log Cards</a>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-lg col-md-4 text-center grading_box">
        <table class="table table-bordered">
            <tr>
                <td class='grading_title' style="background-color: #e02b20; color: #ffffff;">Value Plus</td>
            </tr>
            <tr>
                <td class="">
                    <div class="pricing">40</div>
                    <div>per card</div>
                </td>
            </tr>
            <tr>
                <td>DV < $499</td>
            </tr>
            <tr>
                <td>
                <a href="?type=psa-value_plus" class="btn btn-sm btn-outline-primary">Log Cards</a>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-lg col-md-4 text-center grading_box">
        <table class="table table-bordered">
            <tr>
                <td class='grading_title' style="background-color: #0c71c3; color: #ffffff;">Regular</td>
            </tr>
            <tr>
                <td class="">
                    <div class="pricing">75</div>
                    <div>per card</div>
                </td>
            </tr>
            <tr>
                <td>DV < $1499</td>
            </tr>
            <tr>
                <td>
                <a href="?type=psa-regular" class="btn btn-sm btn-outline-primary">Log Cards</a>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-lg col-md-4 text-center grading_box">
        <table class="table table-bordered">
            <tr>
                <td class='grading_title' style="background-color: #000000; color:  #e09900;">Express</td>
            </tr>
            <tr>
                <td class="">
                    <div class="pricing">165</div>
                    <div>per card</div>
                </td>
            </tr>
            <tr>
                <td>DV < $2499</td>
            </tr>
            <tr>
                <td>
                <a href="?type=psa-express" class="btn btn-sm btn-outline-primary">Log Cards</a>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-lg col-md-4 text-center grading_box">
        <table class="table table-bordered">
            <tr>
                <td class='grading_title'  style="background-color: #e09900;">Super Express</td>
            </tr>
            <tr>
                <td class="">
                    <div class="pricing">330</div>
                    <div>per card</div>
                </td>
            </tr>
            <tr>
                <td>DV < $4999</td>
            </tr>
            <tr>
                <td>
                    <a  href="?type=psa-super_express" class="btn btn-sm btn-outline-primary">Log Cards</a>
                </td>
            </tr>
        </table>
    </div>
</div>

<?php 
    }
?>