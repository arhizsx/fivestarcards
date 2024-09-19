<?php

global $wpdb;

$checkout_post = get_post($params['order_number']);
$checkout_meta = get_post_meta($checkout_post->ID);


$user_id = $checkout_meta["user_id"][0];
$user = get_user_by( "id", $user_id );

$args = array(
    'meta_query' => array(
        'relations' =>  'AND',    
        array(
            'key' => 'checkout_id',
            'value' => $params['order_number']
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

    $card_total_dv = (float) $card["dv"] * $card["quantity"];

    $total_dv = (float) $total_dv + $card_total_dv;
    $cards_count = (float) $cards_count + $card["quantity"];
}


$admin_status = array( "Shipped", "Package Received", "Incomplete Items Shipped" );
$admin_action_status = array( "Package Received");
$admin_graded_status = array( "Completed - Grades Ready", "Active Consignments", "Pending eBay Payment", "Pending Payout", "Paid Out");


$processed_status = array("Processing Order", "Cards Graded");

$grading_order_id = (int) $checkout_meta["grading_orders_id"][0];

if( $grading_order_id > 0 ){

    $sql = "SELECT * FROM grading where order_id =". $grading_order_id . " AND type LIKE '%_file'";
    $grading_files = $this->wpdb->get_results ( $sql );	

    
} else {
    $grading_files = [];
}

?>
<div class="mt-3 mb-4">
    <?php 
        if(isset( $_GET["mode"] )){
    ?>
    <a href="/administrator/grading?mode=<?php echo $_GET["mode"]?>">Back to Orders</a>     
    <?php 
        } else {
    ?>
    <a href="/administrator/grading">Back to Open Orders</a>     
    <?php         
        }
    ?>
</div>

<div class="m-0 p-0">
    <div class="row border-bottom">
        <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12" >
            <div class="row">
                <div class="col-xl-12 mb-3">
                    <div class='order-label'><?php echo $params['title'] ?></div>
                    <div class='order-data'><?php echo $params['order_number'] ?></div>
                </div>
                <div class="col-xl-12 mb-3">
                    <div class='order-label'>Customer</div>
                    <div class='order-data'><?php echo $user->display_name; ?></div>
                </div>
            </div>
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
                    <div class='order-label'>Grading Type</div>
                    <div class='order-data grading'><?php echo $checkout_meta["grading_type"][0] ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Total Declared Value</div>
                    <div class='order-data'>$<?php echo number_format((float)$total_dv, 2, '.', ''); ?></div>
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
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Total Cards</div>
                    <div class='order-data'><?php echo $cards_count; ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Received Cards</div>
                    <div class='order-data'><?php echo $cards_count; ?></div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Missing Cards</div>
                    <div class='order-data'>0</div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <div class='order-label'>Submission #</div>
                    <div class='order-data'><?php echo $checkout_meta["submission_number"][0] ?></div>
                </div>
            </div>

        </div>
    </div>
    <div class="row mt-3">
        <?php 
            if( $checkout_meta["inspection"][0] == "1" ) { 
        ?>
        <H3 style="color: red !important;">INSPECTION SERVICE</H3>
        <?php 
            }
        ?>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <H3 style="color: black !important;">Cards List</H3>
            <?php 
            if( $checkout_meta["status"][0] == "Package Received") 
            { 
            ?>
            <button class="5star_btn btn-sm btn btn-primary mb-3" data-action="item_avlb_in_package_all" data-order_number="<?php echo $params['order_number'] ?>">
                Received All
            </button>
            <?php 
            }
            ?>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 text-end">
            <?php if( $checkout_meta["status"][0] == "Shipped" ) { ?>
            <button class='5star_btn btn btn-primary mb-3' data-action="package_received"  data-order_number="<?php echo $params['order_number'] ?>">
                Package Received
            </button>      
            <?php } ?>
            <?php 
            if( $checkout_meta["status"][0] == "Package Received" ) { 

                if( $posts )
                {
                    $received = 0;
                    $missing = 0;
                    $shipped = 0;

                    foreach($posts as $post)
                    {
                        $meta = get_post_meta($post->ID);
                        if( $meta["status"][0] == "Received" ){
                            $received++;
                        }
                        elseif( $meta["status"][0] == "Not Available" ){
                            $missing++;
                        }
                        elseif( $meta["status"][0] == "Shipped" ){
                            $shipped++;
                        }
                    }

                    if( $received == count($posts) && $shipped == 0){
                        $complete_btn = "";
                    } else {
                        $complete_btn = "d-none";
                    }

                    if( $missing > 0 && $shipped == 0){
                        $missing_btn = "";
                    } else {
                        $missing_btn = "d-none";
                    }
                }
            ?>
            <button class='5star_btn btn btn-danger mb-3 <?php echo $missing_btn; ?>' data-action="cancel_order" data-order_number="<?php echo $params['order_number'] ?>">
                Cancel Order
            </button>      
            <button class='5star_btn btn btn-warning mb-3 <?php echo $missing_btn; ?>' data-action="incomplete_package_contents" data-order_number="<?php echo $params['order_number'] ?>">
                Missing Items
            </button>      
            <button class='5star_btn btn btn-primary mb-3 <?php echo $complete_btn; ?>' data-action="complete_package_contents" data-order_number="<?php echo $params['order_number'] ?>">
                Items Complete
            </button>      
            <?php } ?>

            <?php 
            if( $checkout_meta["status"][0] == "Grading Complete" ) 
            { 
            ?>
            <button class='5star_btn btn btn-success mb-3' data-action="acknowledge_order_request" data-order_number="<?php echo $params['order_number'] ?>">
                Acknowledge Order Request
            </button>      
            <?php 
            } 
            ?> 

            <?php 

            $processing_status = array("Processing Order", "Shipped to PSA / SGC", "Research", "Grading", "Assembly", "QA1", "QA2", "Completed - Grades Ready", "Incomplete Items Shipped" );

            if( in_array( $checkout_meta["status"][0], $processing_status ) ) 
            { 
            ?>
            <button class='5star_btn btn btn-primary mb-3' data-action="set_submission_number" data-order_number="<?php echo $params['order_number'] ?>">
                Set Submission #
            </button>      
            <button class='5star_btn btn btn-success mb-3' data-action="update_status" data-order_number="<?php echo $params['order_number'] ?>">
                Update Status
            </button>      
            <?php 
            } 

            $processing_status = array( "To Ship" );

            if( in_array( $checkout_meta["status"][0], $processing_status ) ) 
            { 

            ?> 
            <button class='5star_btn btn btn-primary mb-3' data-action="package_received"  data-order_number="<?php echo $params['order_number'] ?>">
                Package Received 
            </button>      
            <button class='5star_btn btn btn-secondary mb-3' data-action="view_pdf"  data-order_number="<?php echo $params['order_number'] ?>">
                PDF
            </button>   
            <?php 
            }
            ?>
        </div>
    </div>
    <div class="table-responsive">   
        <table id="card_table" class='table table-sm 5star_logged_cards table-bordered table-striped' data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/order-action") ?>"  data-view_pdf_endpoint="<?php echo get_rest_url(null, "cards-grading/v1/pdf") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
            <thead>
                <tr>
                    <?php if( in_array( $checkout_meta["status"][0], $admin_action_status ) ){ ?>
                        <?php 
                            if( $checkout_meta["status"][0] == "Package Received" ) { 
                                $action_label = "Inside Package";
                            } else {
                                $action_label = "Action";
                            }
                        ?>
                    <th><?php  echo $action_label; ?></th>
                    <?php } ?>
                    <th>ID</th>
                    <th>Year</th>
                    <th>Brand</th>
                    <th>Card #</th>
                    <th>Player Name</th>
                    <th>Status</th>
                    <?php if( in_array( $checkout_meta["status"][0], $admin_graded_status ) ){ ?>
                    <th style="width: 150px;" class="text-start">Grade</th>
                    <th style="width: 200px;" class="text-start">Cert No.</th>
                    <?php } ?>
                    <th class='text-end'>DV</th>
                    <th class="text-end">Grading</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if( $posts ){

                        foreach($posts as $post)
                        {
                            $meta = get_post_meta($post->ID);
                            $card = json_decode($meta['card'][0], true);

                            $card_total_dv = (float) $card["dv"] * $card["quantity"];
                            $card_grading_charge = (float) $card["per_card"] * $card["quantity"];

                            if( in_array($meta["status"][0], array("To Pay - Grade Only", "Shipped", "To Ship", "Received", "Pay Grading", "Graded", "Completed - Grades Ready") ) ){
                                $grading_charge = (float) $grading_charge + $card_grading_charge;
                            }


                ?>
                <?php                             
                    if( in_array( $checkout_meta["status"][0], $admin_graded_status ) == false ){                             
                ?>
                <tr class="admin-card-row" data-post_id="<?php echo $post->ID; ?>" data-card='<?php echo json_encode($card) ?>'>
                    <?php 
                        if( in_array( $checkout_meta["status"][0], $admin_action_status ) ){ 
                    ?>
                    <td>
                        <?php 
                            if( $checkout_meta["status"][0] == "Package Received" ) { 
                        ?>
                            <div class="row">
                                <div class="col-lg-6">
                                    <button class='5star_btn btn-sm btn btn-danger w-100 mb-3' data-action="item_not_avlb_in_package" data-post_id="<?php echo $post->ID; ?>">
                                        No
                                    </button>
                                </div>
                                <div class="col-lg-6">
                                    <button class='5star_btn btn-sm btn btn-success w-100 mb-3' data-action="item_avlb_in_package" data-post_id="<?php echo $post->ID; ?>">
                                        Yes
                                    </button>
                                </div>
                            </div>
                        <?php 
                            } 
                            else { 
                        ?>
                            -
                        <?php 
                            } 
                        ?>
                    </td>
                    <?php 
                        } 
                    ?>
                    <td>
                        <?php  echo $post->ID; ?>
                    </td>
                    <td><?php echo $card["year"]; ?></td>
                    <td><?php echo $card["brand"]; ?></td>
                    <td><?php echo $card["card_number"]; ?><br><small><?php echo $card["attribute"]; ?></small></td>
                    <td><?php echo $card["player"]; ?></td>

                    <?php if( in_array( $checkout_meta["status"][0], $admin_graded_status ) == false ){ ?>
                        <td class=".card_status"><?php echo $meta["status"][0]; ?></td>
                    <?php } ?>

                    <?php 
                        if( in_array( $checkout_meta["status"][0], $admin_graded_status )){                             
                    ?>
                        <td class="text-start">
                            <input type="text" value="" name="grade"  data-name="grade" data-post_id="<?php echo $post->ID; ?>" data-db_id="<?php echo $card['db_id']; ?>" class="form-control form-control-sm card_grade_saving">
                        </td>
                        <td class="text-start">
                            <input type="text" value="" name="certificate_number" data-name="certificate_number" data-post_id="<?php echo $post->ID; ?>" data-db_id="<?php echo $card['db_id']; ?>" class="form-control form-control-sm card_grade_saving">
                        </td>
                    <?php } else { ?>

                    <?php } ?>

                    <td class='text-end'><?php echo "$" . number_format((float)$card["dv"], 2, '.', ''); ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float) $card_grading_charge, 2, '.', ''); ?></td>

                    <?php 
                        } else {
                    ?>
                    <!-- Graded / To Grade -->

                    <?php 

                    $sql = "SELECT * FROM grading WHERE id = " . $card["db_id"];

                    print_r( $sql );

                    $db_row = $this->wpdb->get_results ( $sql );



                    $db_row_data = json_decode($db_row[0]->data, true);



                    if( 
                        array_key_exists( "title", $db_row_data ) == false && 
                        array_key_exists( "certImgFront", $db_row_data )  == false && 
                        array_key_exists( "certImgBack", $db_row_data )  == false 
                    ){

                        
                    ?>
                    <td>
                        <?php  echo $post->ID;                         
                        ?>
                    </td>
                    <td><?php echo $card["year"]; ?></td>
                    <td><?php echo $card["brand"]; ?></td>
                    <td><?php echo $card["card_number"]; ?><br><small><?php echo $card["attribute"]; ?></small></td>
                    <td><?php echo $card["player"]; ?></td>

                    <td class=".card_status"><?php echo $meta["status"][0]; ?></td>

                    <?php 
                        if( in_array( $checkout_meta["status"][0], $admin_graded_status ) ){          
                            if( $meta["status"][0] == "Received" ){                   
                    ?>
                        <td class="text-start">
                            <input type="text" value="" name="grade"  data-name="grade" data-post_id="<?php echo $post->ID; ?>" data-db_id="<?php echo $card['db_id']; ?>" class="form-control form-control-sm card_grade_saving">
                        </td>
                        <td class="text-start">
                            <input type="text" value="" name="certificate_number" data-name="certificate_number" data-post_id="<?php echo $post->ID; ?>" data-db_id="<?php echo $card['db_id']; ?>" class="form-control form-control-sm card_grade_saving">
                        </td>
                    <?php
                            } else {
                    ?>
                        <td class="text-start">
                            --
                        </td>
                        <td class="text-start">
                            --
                        </td>
                    <?php                                
                            }
                         } else { 
                    ?>

                    <?php 
                    } 
                    ?>

                    <td class='text-end'><?php echo "$" . number_format((float)$card["dv"], 2, '.', ''); ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float) $card_grading_charge, 2, '.', ''); ?></td>

                    <?php 
                            }

                        }
                    ?>

                </tr>
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
                <th>Grade</th>
                <th>Photo</th>
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
        
                            if( count($db_row) > 0 ){
                            $db_row_data = json_decode($db_row[0]->data, true);
        
                            if( array_key_exists( "title", $db_row_data ) && array_key_exists( "certImgFront", $db_row_data ) && array_key_exists( "certImgBack", $db_row_data )  ){
        
                ?>
                <tr class='admin-graded-row' data-post_id='<?php echo $post->ID ?>'  data-id="<?php print_r( $db_row[0]->id ) ?>">>
                    <td><?php echo $post->ID ?></td>
                    <td>
                        <input type="text" class="form-control" value="<?php echo $db_row_data["title"] ?>" data-id="<?php print_r( $db_row[0]->id ) ?>">                        
                    </td>
                    <td><input type="text" class="" value="<?php echo $db_row_data["grade"] ?>" data-id="<?php print_r( $db_row[0]->id ) ?>"></td>
                    <td><a href='<?php echo $db_row_data["certImgFront"] ?>'><img width='100px' src='<?php echo $db_row_data["certImgFront"] ?>' target='_blank'></a><a href='<?php echo $db_row_data["certImgBack"] ?>' target='_blank'><img width='100px' src='<?php echo $db_row_data["certImgBack"] ?>'></a></td>
                    <td><?php echo $db_row_data["certificate_number"] ?></td>
                </tr>

                <?php 
                                }
                            }

                        }

                    }
                ?>


            </tbody>
        </table>
    </div>
    <div class='5star_btn_box_bottom w-100'>
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
                <div class="row  mb-2">
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
                    
                    <td class="">
                        <a target="_blank" href="<?php print_r( $file["baseurl"] ) ?>"><?php print_r( $file["name"] ) ?></a>
                    </td>
                    <td class="text-end">
                        <?php print_r( $file["qty"] ) ?>
                    </td>
                    <td class="text-end">
                        <?php print_r( $file["card_show"] ) ?>
                    </td>

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

    <div class='5star_btn_box_admin_bottom w-100 border-top pt-3'>
        <button class="btn border btn-danger 5star_btn" data-action="admin_delete_order" data-order_number="<?php echo $params['order_number'] ?>" >Delete Order</button>
    </div>        
    
</div>


<!-- <div class="modal fade view_card" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Set Card Grade
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="view_card_form_box">
                    <div class="modal-body py-2 px-3">
                        <forn id="set_grade_form">

                            <input type="hidden" name="user_id" value='<?php echo $user_id; ?>'/>
                            <input type="hidden" name="grading" value=''/>
                            <input type="hidden" name="max_dv" value=''/>
                            <input type="hidden" name="post_id" value=''/>
                            <input type="hidden" name="card" value=''/>
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <label for="grade">Grade</label>
                                    <input id="grade_input" type="text" name="grade" style="font-size: 3em !important; text-align: center !important; color: white !important; background-color: black !important;"  value="" data-field_check="required"  class="form-control mb-2"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <label for="certificate_number">Certificate Number</label>
                                    <input id="certificate_number" type="text" name="certificate_number" style="font-size: 3em !important; text-align: center !important; color: white !important; background-color: black !important;"  value="" data-field_check="required"  class="form-control mb-2"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="year">Year</label>
                                    <input type="number" name="year" value="" data-field_check="required"  class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="dv">Declared Value</label>
                                    <input type="number" name="dv" value="" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="brand">Brand</label>
                                    <input type="text" name="brand" value="" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="card_number">Card Number</label>
                                    <input type="text" name="card_number" value="" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="player">Player Name</label>
                                    <input type="text" name="player" value="" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="attribute">Attribute S/N</label>
                                    <input type="text" name="attribute" value="" data-field_check="" class="form-control mb-2" disabled/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                        <button class="btn border btn-primary 5star_btn" data-action="confirm_card_grade" data-order_number="<?php echo $params['order_number']; ?>">Save Grade</button>
                    </div>
                </div>
		</div>
	</div>
</div> -->

<div class="modal fade updatestatusmodal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Update Status
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="view_card_form_box">
                    <div class="modal-body py-2 px-3">
                        <forn id="set_grade_form">
                            <input type="hidden" name="post_id" value=''/>
                            <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="order_number">Order #</label>
                                    <input type="number" name="order_number" value="<?php echo $params['order_number'] ?>" data-field_check="required"  class="form-control mb-2" disabled/>
                                </div>                                
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="grading_type">Grading Type</label>
                                    <input type="text" name="grading_type" value="<?php echo $checkout_meta["grading_type"][0] ?>" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="customer">Customer</label>
                                    <input type="text" name="customer" value="<?php echo $user->display_name; ?>" data-field_check="required" class="form-control mb-3" disabled/>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="old_status">Old Status</label>
                                    <input type="text" name="old_status" value="<?php echo $checkout_meta["status"][0] ?>" data-field_check="required" class="form-control mb-3" disabled/>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="customer_number">Customer #</label>
                                    <input type="number" name="customer_number" value="<?php echo $user->display_name; ?>" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="new_status">New Status</label>
                                    <select data-field_check="required" name="new_status" class="form-control mb-2">
                                        <option value="">Select New Status</option>
                                        <option value="Processing Order">Processing Order</option>
                                        <option value="Shipped to PSA / SGC">Shipped to PSA / SGC</option>
                                        <option value="Research">Research</option>
                                        <option value="Grading">Grading</option>
                                        <option value="Assembly">Assembly</option>
                                        <option value="QA1">QA1</option>
                                        <option value="QA2">QA2</option>
                                        <option value="Completed - Grades Ready">Completed - Grades Ready</option>
                                        <option value="Active Consignments">Active Consignments</option>
                                        <option value="Pending eBay Payment">Pending eBay Payment</option>
                                        <option value="Pending Payout">Pending Payout</option>
                                        <option value="Paid Out">Paid Out</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                        <button class="btn border btn-primary 5star_btn" data-action="confirm_new_order_status" data-order_number="<?php echo $params['order_number']; ?>">Set Status</button>
                    </div>
                </div>
		</div>
	</div>
</div>

<div class="modal fade setsubmissionmodal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Set Submission Number
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="view_card_form_box">
                    <div class="modal-body py-2 px-3">
                        <forn id="set_grade_form">
                            <input type="hidden" name="post_id" value=''/>
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <label for="submission_number">Submission Number</label>
                                    <input id="submission_number" type="text" name="submission_number" style="font-size: 3em !important; text-align: center !important; color: white !important; background-color: black !important;"  value="" data-field_check="required"  class="form-control mb-2"/>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="order_number">Order #</label>
                                    <input type="number" name="order_number" value="<?php echo $params['order_number'] ?>" data-field_check="required"  class="form-control mb-2" disabled/>
                                </div>                                
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="grading_type">Grading Type</label>
                                    <input type="text" name="grading_type" value="<?php echo $checkout_meta["grading_type"][0] ?>" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="customer_number">Customer #</label>
                                    <input type="number" name="customer_number" value="<?php echo $user->display_name; ?>" data-field_check="required" class="form-control mb-2" disabled/>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <label for="status">Status</label>
                                    <input type="text" name="status" value="<?php echo $checkout_meta["status"][0] ?>" data-field_check="required" class="form-control mb-3" disabled/>
                                </div>
                                <div class="col-xl-12">
                                    <label for="customer">Customer</label>
                                    <input type="text" name="customer" value="<?php echo $user->display_name; ?>" data-field_check="required" class="form-control mb-3" disabled/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                        <button class="btn border btn-primary 5star_btn" data-action="confirm_submission_number" data-order_number="<?php echo $params['order_number']; ?>">Set Submission #</button>
                    </div>
                </div>
		</div>
	</div>
</div>

<div class="modal fade delete_order" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Delete Order
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="view_card_form_box">
                    <div class="modal-body py-2 px-3">
                        <forn id="delete_order_form">

                            <input type="hidden" name="user_id" value='<?php echo $user_id; ?>'/>
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <label for="order_number">Order Number</label>
                                    <input id="order_number" type="number" name="order_number" style="font-size: 3em !important; text-align: center !important; color: white !important; background-color: red !important;"  value="<?php echo $params['order_number'] ?>" data-field_check="required" disabled  class="form-control mb-2"/>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    Are you sure you want to delete this order? This action cannot be reverted.
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                        <button class="btn border btn-danger 5star_btn" data-action="confirm_admin_delete_order" data-order_number="<?php echo $params['order_number'] ?>" data-back="/administrator/grading/">Confirm Delete</button>
                    </div>
                </div>
		</div>
	</div>
</div>
