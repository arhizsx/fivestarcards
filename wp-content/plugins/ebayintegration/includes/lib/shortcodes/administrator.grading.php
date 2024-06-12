
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <H3 style="color: black;">Grading</H3>
        </div>
        <div class="col-12">
            <a href="/administrator" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-list me-2"></i>
                Dashboard
            </a>
            <a href="/administrator/grading" class="5star_btn btn text-left  btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/administrator/consignment" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/administrator/ebay" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-e me-2"></i>
                eBay
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
                <ul class="clearfix d-none d-lg-block">
                    <li class="<?php echo AdministratorGrading("order_receiving"); ?>">
                        <a class="" href="/administrator/grading">Receiving</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("open_orders"); ?>">
                        <a class="" href="/administrator/grading?mode=open_orders">Open</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("awaiting_payment"); ?>">
                        <a class="" href="/administrator/grading/?mode=awaiting_payment">Awaiting Payment</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("consignment"); ?>">
                        <a class="" href="/administrator/grading/?mode=consignment">Consignment</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("completed"); ?>">
                        <a class="" href="/administrator/grading/?mode=completed">Completed</a>
                    </li>
                    <li class="<?php echo AdministratorGrading("cancelled"); ?>">
                        <a class="" href="/administrator/grading/?mode=cancelled">Cancelled</a>
                    </li>
                </ul>
                <div class="d-lg-none p-3">
                    <label>Select Listing Type / Status</label>
                    <select class="form-control" id="mobile_tab_select">
                        <option value="/my-account-v2" <?php echo ActivateListingSelect("auction_items") ?>>Auction</option>
                        <option value="/my-account-v2/?mode=fixed_price_items" <?php echo ActivateListingSelect("fixed_price_items") ?>>Fixed Price</option>
                        <option value="/my-account-v2/?mode=awaiting_payment_items" <?php echo ActivateListingSelect("awaiting_payment_items") ?>>Awaiting Payment</option>
                        <option value="/my-account-v2/?mode=pending_payout_items" <?php echo ActivateListingSelect("pending_payout_items") ?>>Pending Payout</option>
                        <option value="/my-account-v2/?mode=paid_out" <?php echo ActivateListingSelect("paid_out") ?>>Paid Out</option>
                    </select>
                </div>

                <ul class="clearfix d-lg-none">
                    <li class="">
                        <a class="" href="/administrator/grading">Receiving</a>
                    </li>

                </ul>
                <div class="content p-3">
                    <?php 

                        if( isset( $_GET['mode'] )  == false){

                            $shortcode = "[cards-grading-orders_table table='order_receiving']";
                            
                            
                        } 
                        else {

                            switch( $_GET["mode"] ){

                                case "open_orders": 
                                    $shortcode = "[cards-grading-orders_table table='open_orders']";                                    
                                    break;

                                case "awaiting_payment": 
                                    $shortcode = "[cards-grading-orders_table table='awaiting_payment']";                                    
                                    break;

                                case "consignment": 
                                    $shortcode = "[cards-grading-orders_table table='consigned_for_payment']";
                                    break;

                                case "completed": 
                                    $shortcode = "[cards-grading-orders_table table='completed_orders']";
                                    break;

                                case "cancelled": 
                                    $shortcode = "[cards-grading-orders_table table='cancelled_orders']";
                                    break;

                                default:
                                    $shortcode = "[cards-grading-orders_table table='order_receiving']";
                                    break;

                            }

                        }


                        echo do_shortcode( $shortcode );                    

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
