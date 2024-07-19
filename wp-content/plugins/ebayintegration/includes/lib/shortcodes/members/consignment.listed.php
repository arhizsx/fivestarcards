
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("auction") ?>" href="/my-account/consignment/?mode=listed&type=auction">Auction</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("fixed_price") ?>" href="/my-account/consignment/?mode=listed&type=fixed_price">Fixed Price</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("awaiting_payment") ?>" href="/my-account/consignment/?mode=listed&type=awaiting_payment">Awaiting Payment</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("cancelled") ?>" href="/my-account/consignment/?mode=listed&type=cancelled">Cancelled</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("pending_payout") ?>" href="/my-account/consignment/?mode=listed&type=pending_payout">Pending Payout</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("paid_out") ?>" href="/my-account/consignment/?mode=listed&type=paid_out">Paid Out</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php 
            if(isset( $_GET['type']) ){
                include( plugin_dir_path( __FILE__ ) . "consignment.listed." . $_GET['type'] . '.php' );			

            } else {
                include( plugin_dir_path( __FILE__ ) . 'consignment.listed.auction.php' );			
 
            }
            ?>

        </div>
    </div>
</div>
