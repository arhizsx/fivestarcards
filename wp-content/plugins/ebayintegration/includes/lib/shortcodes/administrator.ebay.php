
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
            <a href="/administrator/grading" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/administrator/consignment" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/administrator/payouts" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
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
                <ul class="clearfix">
                    <li class="<?php echo AdministratorEbay("auction"); ?>">
                        <a class="" href="/administrator/ebay">Auction</a>
                    </li>
                    <li class="<?php echo AdministratorEbay("fixed_price"); ?>">
                        <a class="" href="/administrator/ebay/?mode=fixed_price">Fixed Price</a>
                    </li>
                    <li class="<?php echo AdministratorEbay("awaiting_payment"); ?>">
                        <a class="" href="/administrator/ebay/?mode=awaiting_payment">Awaiting Payment</a>
                    </li>
                    <li class="<?php echo AdministratorEbay("pending_payout"); ?>">
                        <a class="" href="/administrator/ebay/?mode=pending_payout">Pending Payout</a>
                    </li>
                    <li class="<?php echo AdministratorEbay("paid_out"); ?>">
                        <a class="" href="/administrator/ebay/?mode=paid_out">Paid Out</a>
                    </li>
                </ul>
                <div class="content p-3">
                    <?php 
                        if( isset( $_GET['mode'] )  == false){

                            $shortcode = "[ebayintegration-shortcode type='auction_items_admin']";                            
                            
                        } 
                        else {

                            switch( $_GET["mode"] ){

                                case "fixed_price": 
                                    $shortcode = "[ebayintegration-shortcode type='auction_items_admin']";                            
                                    break;

                                case "awaiting_payment": 
                                    $shortcode = "[ebayintegration-shortcode type='awaiting_payment_items_admin']";                            
                                    break;

                                case "pending_payout": 
                                    $shortcode = "[ebayintegration-shortcode type='pending_payout_items_admin']";                            
                                    break;

                                case "paid_out": 
                                    $shortcode = "[ebayintegration-shortcode type='paid_out_admin']";                            
                                    break;

                                default:
                                    $shortcode = "[ebayintegration-shortcode type='auction_items_admin']";                            
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
