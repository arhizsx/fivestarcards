
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

$popular = $this->wpdb->get_results ( "
SELECT * 
FROM (
    SELECT * 
    FROM view_fixed_price
    ORDER BY WatchCount DESC
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
            <div class="d-flex align-items-start col-md-2 col-sm-4 mb-3 text-center">
                <div>
                    <div class="height: 120px; min-height: 120px; max-height: 120px;">
                        <a href="<?php echo $item->ViewItemURL ?>" target="_blank">
                            <img style="margin-top: auto; margin-bottom: auto; height: 100%;" src="<?php echo $item->GalleryURL ?>">
                        </a>
                    </div>
                    <div class="px-2 mt-2 d-flex justify-content-between" style="font-size: 12px; color: black; font-weight:bold;">
                        <div>Bids: <?php echo $item->BidCount; ?></div>
                        <div class="text-end">$<?php echo $item->CurrentPrice; ?></div>
                    </div>
                    <div style="font-size: 12px;">
                        <a href="<?php echo $item->ViewItemURL ?>" target="_blank">
                            <?php echo $item->Title; ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php 
            }
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <H1>Popular Items</H1>
            </div>
        </div>
        <div class="row">
            <?php 
            foreach($popular as $item){ 
            ?>
            <div class="d-flex align-items-start col-md-2 col-sm-4 mb-3 text-center">
                <div>
                    <div class="height: 120px; min-height: 120px; max-height: 120px;">
                        <a href="<?php echo $item->ViewItemURL ?>?mkcid=1&mkrid=711-53200-19255-0&siteid=0&campid=5339081621&customid=&toolid=10001&mkevt=1" target="_blank">
                            <img style="margin-top: auto; margin-bottom: auto; height: 100%;" src="<?php echo $item->GalleryURL ?>">
                        </a>
                    </div>
                    <div class="px-2 mt-2 d-flex justify-content-between" style="font-size: 12px; color: black; font-weight:bold;">
                        <div>Watchers: <?php echo $item->WatchCount; ?></div>
                        <div class="text-end">$<?php echo $item->CurrentPrice; ?></div>
                    </div>
                    <div style="font-size: 12px;">
                        <a href="<?php echo $item->ViewItemURL ?>?mkcid=1&mkrid=711-53200-19255-0&siteid=0&campid=5339081621&customid=&toolid=10001&mkevt=1" target="_blank">
                            <?php echo $item->Title; ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php 
            }
            ?>
        </div>
    </div>
</div>


