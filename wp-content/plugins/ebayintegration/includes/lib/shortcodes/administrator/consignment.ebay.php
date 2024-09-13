<div class="container-fluid">
    <div class="row">
        <div class="col">
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("scheduled") ?>" href="/administrator/consignment/?mode=ebay&type=scheduled">Scheduled</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("auction") ?>" href="/administrator/consignment/?mode=ebay&type=auction">Auction</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("fixed_price") ?>" href="/administrator/consignment/?mode=ebay&type=fixed_price">Fixed Price</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("awaiting_payment") ?>" href="/administrator/consignment/?mode=ebay&type=awaiting_payment">Awaiting Payment</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("unsold") ?>" href="/administrator/consignment/?mode=ebay&type=unsold">Unsold</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("cancelled") ?>" href="/administrator/consignment/?mode=ebay&type=cancelled">Cancelled</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("pending_payout") ?>" href="/administrator/consignment/?mode=ebay&type=pending_payout">Pending Payout</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("paid_out") ?>" href="/administrator/consignment/?mode=ebay&type=paid_out">Paid</a>
        </div>
    </div>
    <div class="row">
        <div class="col pt-3">
            <?php 
            if(isset( $_GET['type']) ){
                include( plugin_dir_path( __FILE__ ) . "consignment.ebay." . $_GET['type'] . '.php' );			

            } else {
                include( plugin_dir_path( __FILE__ ) . 'consignment.ebay.auction.php' );			
 
            }
            ?>

        </div>
    </div>

</div>