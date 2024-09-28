
<?php 
$hot = $this->wpdb->get_results ( "
SELECT * 
FROM (
    SELECT * 
    FROM view_auction
    WHERE ListingType='Chinese'
    ORDER BY BidCount DESC
    LIMIT 20
) AS top_20
ORDER BY RAND()
LIMIT 6;    
" 
);
?>


<div class="container-fluid">
    <div class="row">
        <div class="col">
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("scheduled") ?>" href="/my-account/consignment/?mode=listed&type=scheduled">Scheduled</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("auction") ?>" href="/my-account/consignment/?mode=listed&type=auction">Auction</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("fixed_price") ?>" href="/my-account/consignment/?mode=listed&type=fixed_price">Fixed Price</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("awaiting_payment") ?>" href="/my-account/consignment/?mode=listed&type=awaiting_payment">Awaiting Payment</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("unsold") ?>" href="/my-account/consignment/?mode=listed&type=unsold">Unsold</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("cancelled") ?>" href="/my-account/consignment/?mode=listed&type=cancelled">Cancelled</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("pending_payout") ?>" href="/my-account/consignment/?mode=listed&type=pending_payout">Pending Payout</a>
            <a class="btn btn-pill btn-sm mb-2 <?php echo ActivateListing("paid_out") ?>" href="/my-account/consignment/?mode=listed&type=paid_out">Paid</a>
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
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <H1>Hot Auctions</H1>
            </div>
        </div>
        <div class="row">
            <?php 
            foreach($hot as $item){ 
            ?>
            <div class="col-md-2 col-sm-4 mb-3 text-center">
                <a href="#">
                    <div class="height: 120px; min-height: 120px; max-height: 120px;">
                        <img style="margin-top: auto; margin-bottom: auto; height: 100%;" src="<?php echo $item->GalleryURL ?>">
                    </div>
                    <div>$<?php echo $item->CurrentPrice; ?></div>
                    <div style="font-size: 12px;">
                        <?php echo $item->Title; ?>
                    </div>
                </a>
            </div>
            <?php 
            }
            ?>
        </div>
    </div>
</div>


