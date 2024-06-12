
<?php 

    include( plugin_dir_path( __FILE__ ) . "css.php" );			

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <H3 style="color: black;">My Listings</H3>
        </div>
        <div class="col-12">
            <a href="/my-account" class="5star_btn btn text-left  btn-dark mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-list me-2"></i>
                My Listings
            </a>
            <a href="/my-account/grading" class="5star_btn btn text-left  btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-circle-dot me-2"></i>                
                Grading
            </a>
            <a href="/my-account/consignment" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-handshake me-2"></i>
                Consignment
            </a>
            <a href="/my-account/cashout" class="5star_btn btn text-left btn-secondary mb-3" data-type="psa-value_bulk" data-action="add_card">
                <i class="fa-solid fa-money-bill me-2"></i>
                Cashout
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="shortcode_tab_box">
                <ul class="clearfix d-none d-lg-block">
                    <li class="<?php echo ActivateListing("auction_items"); ?>">
                        <a class="" href="/my-account-v2">Auction</a>
                    </li>
                    <li class="<?php echo ActivateListing("fixed_price_items"); ?>">
                        <a class="" href="/my-account-v2/?mode=fixed_price_items">Fixed Price</a>
                    </li>
                    <li class="<?php echo ActivateListing("awaiting_payment_items"); ?>">
                        <a class="" href="/my-account-v2/?mode=awaiting_payment_items">Awaiting Payment</a>
                    </li>
                    <li class="<?php echo ActivateListing("pending_payout_items"); ?>">
                        <a class="" href="/my-account-v2/?mode=pending_payout_items">Pending Payout</a>
                    </li>
                    <li class="<?php echo ActivateListing("paid_out"); ?>">
                        <a class="" href="/my-account-v2/?mode=paid_out">Paid Out</a>
                    </li>
                </ul>
                <div class="d-lg-none p-3">
                    <label>Select Listing Type</label>
                    <select class="form-control" id="mobile_tab_select">
                        <option value="/my-account-v2" <?php echo ActivateListingSelect("auction_items") ?>>Auction</option>
                        <option value="/my-account-v2/?mode=fixed_price_items" <?php echo ActivateListingSelect("fixed_price_items") ?>>Fixed Price</option>
                        <option value="/my-account-v2/?mode=awaiting_payment_items" <?php echo ActivateListingSelect("awaiting_payment_items") ?>>Awaiting Payment</option>
                        <option value="/my-account-v2/?mode=pending_payout_items" <?php echo ActivateListingSelect("pending_payout_items") ?>>Pending Payout</option>
                        <option value="/my-account-v2/?mode=paid_out" <?php echo ActivateListingSelect("paid_out") ?>>Paid Out</option>
                    </select>
                </div>
                <div class="content p-3">
                    <?php 
                    if(isset( $_GET['mode']) ){
                        include( plugin_dir_path( __FILE__ ) .  $_GET['mode'] . '.php' );			

                    } else {
                        include( plugin_dir_path( __FILE__ ) . 'auction_items.php' );			

                    }
                    ?>
                </div>
            </div>


        </div>
    </div>
</div>
