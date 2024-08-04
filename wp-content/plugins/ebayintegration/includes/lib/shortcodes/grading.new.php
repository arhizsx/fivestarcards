<style>
    h2, h3 {
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
    .picture_box {
        width: auto; 
        min-height: 120px; 
        max-height: 120px; 
        color: black;
        background-color: lightgray; 
        cursor: pointer;
    }
</style>

<?php 


    if( isset( $_GET["type"] ) ){

        global $wpdb;

        $consignment = $this->wpdb->get_results ( "
        SELECT * 
        FROM grading
        where user_id = " . get_current_user_id() . " 
        and status = 'logged'
        and type = '" . $_GET['type']  . "'
        order by id desc
        "
        );        

        switch( $_GET["type"] ){

            case "psa-value_bulk": 
                $grading_title = "PSA Value Bulk";
                $max_dv = 499;
                $per_card = 19;
                break;

            case "psa-value_bulk_vintage": 
                $grading_title = "PSA Value Bulk Vintage";
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

            case "sgc-bulk": 
                $grading_title = "SGC Bulk";
                $max_dv = 0;
                $per_card = 19;
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

        <!-- UPPER BUTTONS -->
        <div>
            <?php 
                if( isset($_GET["grader"]) ){
                    $grd = "?grader=" . $_GET["grader"];
                } else {
                    $grd = "";
                }
            ?>
            <a href="/my-account/grading/new<?php echo $grd; ?>" class="btn btn-outline-dark mb-3 ">Back to Grading Types</a>

            <button class="btn btn-success mb-3 ebayintegration-btn" data-action="show_log_grading_modal">
                Log Card
            </button>
            <!-- <button class="btn btn-dark mb-3 ebayintegration-btn" data-action="show_import_grading_modal">
                Import Cards List
            </button> -->
        </div>

        <!-- DESKTOP VIEW -->
        <div class="table-responsive d-none d-lg-block">
            <table class="table table-sm table-bordered" id="new_grading">                
                <thead>
                    <tr>
                        <th style="width: 20px;"></th>
                        <th style="width: 100px;">Photo</th>
                        <th width="30%">Player Name</th>
                        <th>Year</th>
                        <th>Brand</th>
                        <th>Card Number</th>
                        <th class="text-end">DV</th>
                        <th class="text-end">Grading</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total_dv = 0;
                        $total_grading = 0;

                        if( count( $consignment ) == 0 ){
                    ?>
                    <tr class="empty_grading">
                        <td colspan="8" class="text-center py-5">
                            Empty
                        </td>
                    </tr>
                    <?php 
                        } else {


                            foreach( $consignment as $card ){

                                $data = json_decode( $card->data, true );

                                if(array_key_exists("file", $data)){
                                    $img = "<img src='" . ($data["file"]["baseurl"]) . "'>";
                                } else {
                                    $img = '<div class="d-flex justify-content-center align-items-center picture_box ebayintegration-btn" data-action="grading_picture_box_click"  data-id="' . $card->id . '"   data-user_id="' . get_current_user_id() . '">' .
                                            '<i class="fa-solid fa-file-image fa-2x"></i>' . 
                                        '</div>';
                                }

                    ?>
                    <tr class='consigned_item_row' data-id='<?php echo $card->id; ?>'>
                        <td>
                            <a class='text-danger  ebayintegration-btn' data-action="removeGradingCardRow"  data-id='<?php echo $card->id ?>' data-user_id="<?php echo get_current_user_id(); ?>" href='#'>
                                <i class='fa-solid fa-lg fa-xmark'></i>
                            </a>
                        </td>
                        <td style="width: 100px; padding: 0px;">
                            <div class="ebayintegration-btn"  data-action="grading_picture_box_click"  data-id="<?php echo $card->id ?>"   data-user_id="<?php echo get_current_user_id() ?>">
                            <?php echo $img ?>
                            </div>
                        </td>
                        <td><?php echo $data["player"] ?></td>
                        <td><?php echo $data["year"] ?></td>
                        <td><?php echo $data["brand"] ?></td>
                        <td><?php echo $data["card_number"] ?><br><small><?php echo $data["attribute_sn"] ?></small></td>
                        <td class='text-end'>$<?php echo $data["dv"] ?></td>
                        <td class='text-end'>$<?php echo $data["per_card"] ?></td>
                    </tr>
                    <?php 
                                $total_grading = $total_grading + $data["per_card"];
                                $total_dv = $total_dv + $data["dv"];

                            }
                        }
                    ?>  
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan='7' class="text-end">
                            Total Inspection Service
                        </th>
                        <th colspan='1' class="text-end">
                            $<?php echo count($consignment) * 3 ?>
                        </th>
                    </tr>

                    <tr>
                        <th colspan='7' class="text-end">Total DV</th>
                        <th colspan='1' class="text-end">$<?php echo $total_dv ?></th>
                    </tr>
                    <tr>
                        <th colspan='7' class="text-end">Grading Charge</th>
                        <th colspan='1' class="text-end">$<?php echo $total_grading ?></th>
                    </tr>
                    <tr>
                        <td colspan="8" class="bg-light py-3 text-dark">
                            <input type="checkbox" id="service" name="service" class="me-3" value="inspection_service"><strong>Include Inspection Service</strong> (This will be an additional charge of $3 per card)
                        </td>
                    </tr>

                </tfoot>        
            </table>
        </div>

        <!-- MOBILE VIEW -->
        <div class="d-lg-none pb-2">
            <table class="table table-sm table-bordered" id="new_grading_mobile">
                <thead>
                    <tr>
                        <th colspan="2">Items</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total_dv = 0;
                        $total_grading = 0;

                        if( count( $consignment ) == 0 ){
                    ?>
                    <tr class="empty_grading">
                        <td colspan="2" class="text-center py-5">
                            Empty
                        </td>
                    </tr>
                    <?php 
                        } else {
                            foreach( $consignment as $card ){

                            $data = json_decode( $card->data, true );

                            if(array_key_exists("file", $data)){
                                $img = "<img src='" . ($data["file"]["baseurl"]) . "'>";
                            } else {
                                $img = '<div class="d-flex justify-content-center align-items-center picture_box">' .
                                            '<i class="fa-solid fa-file-image fa-2x"></i>' . 
                                        '</div>';

                            }   

                    ?>
                    <tr class='consigned_item_row' data-id='<?php echo $card->id; ?>'>
                        <td colspan="2">
                            <div class='w-100 p-0 text-end' style='position: relative;'>
                                <a class='text-danger ebayintegration-btn' data-action="removeGradingCardRow" data-id='<?php echo $card->id ?>' data-user_id="<?php echo get_current_user_id(); ?>" href='#' style='position: absolute; right: 0px;'>
                                    <i class='fa-solid fa-xl fa-xmark'></i>
                                </a>
                            </div>
                            <div class='row'>
                                <div class='small text-secondary col-sm-4'>Player</div>
                                <div class='col-sm-8'>                                    
                                    <?php echo $data["player"] ?>								
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
                                    $0.00                        
                                </div>
                            </div>
                            <div class='row'>
                                <div class='small text-secondary col-sm-4'>Grading</div>
                                <div class='col-sm-8'>
                                    $0.00
                                </div>
                            </div>
                            <div class='row'>
                                <div class='small text-secondary col-sm-4'>Photo</div>
                                <div class='col-sm-8'>
                                    <div class="ebayintegration-btn"  data-action="grading_picture_box_click"  data-id="<?php echo $card->id ?>"   data-user_id="<?php echo get_current_user_id() ?>">
                                    <?php echo $img ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php
                            $total_grading = $total_grading + $data["per_card"];
                            $total_dv = $total_dv + $data["dv"];

                            }
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan='1' class="text-end">
                            Total Inspection Service
                        </th>
                        <th colspan='1' class="text-end">
                            $<?php echo count($consignment) * 3 ?>
                        </th>
                    </tr>

                    <tr>
                        <th colspan='1' class="text-end">Total DV</th>
                        <th colspan='1' class="text-end">$<?php echo $total_dv ?></th>
                    </tr>
                    <tr>
                        <th colspan='1' class="text-end">Grading Charge</th>
                        <th colspan='1' class="text-end">$<?php echo $total_grading ?></th>
                    </tr>
                </tfoot>        
            </table>    
        </div>

        <!-- TABLE LOWER BUTTONS -->
        <div class="d-flex justify-content-end">
            <button class="btn btn-danger mb-3 me-2 ebayintegration-btn" data-action="show_grading_table_clear_list" data-user_id="<?php echo get_current_user_id(); ?>" data-grading_type="<?php echo $_GET["type"]; ?>">
                Clear List
            </button>
            <button class="btn btn-primary mb-3  ebayintegration-btn" data-action="show_grading_table_checkout" data-user_id="<?php echo get_current_user_id(); ?>" data-grading_type="<?php echo $_GET["type"]; ?>">
                Checkout
            </button>
        </div>


        <!-- LOG MODAL -->
        <div class="modal fade log_grading_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
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

                            <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">
                            <input type="hidden" name="grading_type" value="<?php echo $_GET["type"]; ?>">

                            <div class="col-md-12">
                                <label>Grading</label>
                                <input type="text" name="grading" class="form-control p-1" value="<?php echo $grading_title; ?>" disabled>
                            </div>
                            <div class="col-sm-6">
                                <label>Grading Charge Per Card</label>
                                <input type="text" name="per_card" class="form-control p-1" value="<?php echo $per_card; ?>" disabled>
                            </div>
                            <div class="col-sm-6">
                                <label>Max Declared Value</label>
                                <input type="text" name="max_dv" class="form-control p-1" value="<?php echo $max_dv; ?>" disabled>
                            </div>
                            <div class="col-6">                                                        
                                <label>Qty</label>
                                <input type="number" name="quantity" class="form-control" value="">
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
                                <input type="text" name="player" class="form-control p-1" value="">
                            </div>
                            <div class="col-sm-12">
                                <label>Card Number</label>
                                <input type="text" name="card_number" class="form-control p-1" value="">
                            </div>
                            <div class="col-sm-6">
                                <label>Attribute S/N</label>
                                <input type="text" name="attribute_sn" class="form-control p-1" value="">
                            </div>
                            <div class="col-sm-6">
                                <label>Declared Value</label>
                                <input type="text" name="dv" class="form-control p-1" value="">
                            </div>
                        </div>
                        <div class="d-none p-5 text-center loading">

                            Adding card, please wait...

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>

                        <button class="btn border btn-success ebayintegration-btn" 
                            data-action='confirmAddGrading' 
                        >
                            Log Card
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- IMPORT MODAL -->
        <div class="modal fade import_grading_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
            <div class="modal-dialog" id="dxmodal">
                <div class="modal-content modal-ajax">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">
                            Import Cards List
                        </h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                            X
                        </button>
                    </div>
                    <div class="modal-body py-2 px-3">
                        <div class="row formbox">
                            <div class="col-12">
                                <label>Select Import Template</label>
                                <input type="file" name="import_file" class="form-control mb-3">
                            </div>
                            <div class="col-12 mb-3">
                                <a href="#">Download Import Template</a>
                            </div>
                        </div>
                        <div class="d-none p-5 text-center loading">

                            Importing cards list, please wait...

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>

                        <button class="btn border btn-success ebayintegration-btn" 
                            data-action='confirmAddGrading' 
                        >
                            Log Card
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- PICTURE MODAL -->
        <div class="modal fade picture_box_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
            <div class="modal-dialog" id="dxmodal">
                <div class="modal-content modal-ajax">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">
                            Upload Photo of Card
                        </h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                            X
                        </button>
                    </div>
                    <div class="modal-body py-2 px-3">
                        <div class="row formbox">
                            <form class="form" id="photo_upload_form">
                            <div class="col-12">

                                <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">
                                <input type="hidden" name="card_id" value="">
                                <input type="hidden" name="action" value="confirmPhotoAdd">

                                <label>Select Card Photo</label>
                                <input type="file" name="card_photo" class="form-control mb-3 checker" data-checker="required">
                            </div>
                            </form>
                        </div>
                        <div class="d-none p-5 text-center loading">

                            Uploading card photo, please wait...

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>

                        <button class="btn border btn-success ebayintegration-btn" 
                            data-action='confirmPhotoAdd' 
                        >
                            Upload Photo
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- CLEAR MODAL -->
        <div class="modal fade clear_grading_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
            <div class="modal-dialog" id="dxmodal">
                <div class="modal-content modal-ajax">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">
                            Clear Logged Cards
                        </h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                            X
                        </button>
                    </div>
                    <div class="modal-body py-2 px-3">
                        <div class="row formbox">
                            <div class="col-12 p-5">
                                Are you sure want to remove all logged cards?
                            </div>
                        </div>
                        <div class="d-none p-5 text-center loading">

                            Clearing cards list, please wait...

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Cancel</button>

                        <button class="btn border btn-danger ebayintegration-btn" 
                            data-action='confirmGradingTableClearList'  data-user_id="<?php echo get_current_user_id(); ?>" data-grading_type="<?php echo $_GET["type"]; ?>"
                        >
                            Yes
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- CHECKOUT MODAL -->
        <div class="modal fade checkout_grading_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
            <div class="modal-dialog" id="dxmodal">
                <div class="modal-content modal-ajax">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">
                            Checkout
                        </h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                            X
                        </button>
                    </div>
                    <div class="modal-body py-2 px-3">
                        <div class="row formbox">
                            <div class="col-12 p-5">
                                Are you sure want to checkout the logged cards?
                            </div>
                        </div>
                        <div class="d-none p-5 text-center loading">

                            Checking Out, please wait...

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Cancel</button>
                        <button class="btn border btn-primary ebayintegration-btn" data-action='confirmGradingTableCheckout'  data-user_id="<?php echo get_current_user_id(); ?>" data-grading_type="<?php echo $_GET["type"]; ?>" >
                            Yes
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

<!-- GRADING MENU -->

<?php
    } else {

        if( get_current_user_id() != 0 ){

            if( ! isset( $_GET["grader"] ) || $_GET["grader"] == "psa"  ){
?>
    <div class="row mx-3 mb-3">
        <div class="col-12">
            <H2>PSA Grading &emsp; <a style="text-decoration: none; color: lightgray;" href='?grader=sgc'>SGC Grading</a></H2>
        </div>
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
                    <a href="?grader=psa&type=psa-value_bulk" class="btn btn-sm btn-outline-primary">Log Cards</a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-lg col-md-4 text-center grading_box">
            <table class="table table-bordered">
                <tr>
                    <td class='grading_title' style="background-color: #005e0c; color: #ffffff;">Value Bulk Vintage</td>
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
                    <a href="?grader=psa&type=psa-value_bulk_vintage" class="btn btn-sm btn-outline-primary">Log Cards</a>
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
                    <a href="?grader=psa&type=psa-value_plus" class="btn btn-sm btn-outline-primary">Log Cards</a>
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
                    <a href="?grader=psa&type=psa-regular" class="btn btn-sm btn-outline-primary">Log Cards</a>
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
                    <a href="?grader=psa&type=psa-express" class="btn btn-sm btn-outline-primary">Log Cards</a>
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
                        <a  href="?grader=psa&type=psa-super_express" class="btn btn-sm btn-outline-primary">Log Cards</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php
        } 
            elseif( isset( $_GET["grader"] ) && $_GET["grader"] == "sgc"  ){
?>
    <div class="row mx-3">
        <div class="col-12">
            <H2><a style="text-decoration: none; color: lightgray;" href='?grader=psa'>PSA Grading</a> &emsp; SGC Grading</H2>
        </div>
        <div class="col-md-3 text-center grading_box">
            <table class="table table-bordered">
                <tr>
                    <td class='grading_title' style="background-color: #0c71c3; color: #ffffff;">
                        Bulk<br>
                        <small>20+ Cards</small>
                    </td>
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
                    <a href="?grader=sgc&type=sgc-bulk" class="btn btn-sm btn-outline-primary">Log Cards</a>
                    </td>
                </tr>
            </table>
        </div>    
    </div>
<?php    
            }
        } else {
?>
    <H1 style="color: black;">Coming Soon</H1>
    <a href="/grading">Log Cards for Grading</a>
<?php 
        }
    }
?>