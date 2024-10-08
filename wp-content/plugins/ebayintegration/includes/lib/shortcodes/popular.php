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
LIMIT 8;    
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
LIMIT 8;    
" 
);

?>
<style>
    .itemTitle {
        line-height: 1.5em;
        height: 3em;
        overflow: hidden;
        text-overflow: ellipsis;
    }    
</style>
<div class="container-fluid px-3">
    <div class="row">
        <div class="col">
            <div class="row">
                <div class="col">
                    <H1 style="color: black;">Hot eBay Auctions</H1>
                    <p>You will be redirected to an ebay listing</p>
                </div>
            </div>
            <div class="row">
                <?php 
                foreach($hot as $item){ 
                ?>
                <div class="d-flex align-items-start col-md-3 col-sm-6 mb-3 text-center">
                    <div>
                        <div style="height: 120px; min-height: 120px; max-height: 120px;">
                            <a href="<?php echo $item->ViewItemURL ?>?mkcid=1&mkrid=711-53200-19255-0&siteid=0&campid=5339081621&customid=&toolid=10001&mkevt=1" target="_blank">
                                <img style="margin-top: auto; margin-bottom: auto; height: 100%;" src="<?php echo $item->GalleryURL ?>">
                            </a>
                        </div>
                        <div class="itemTitle">
                            <a href="<?php echo $item->ViewItemURL ?>?mkcid=1&mkrid=711-53200-19255-0&siteid=0&campid=5339081621&customid=&toolid=10001&mkevt=1" target="_blank">
                                <?php echo $item->Title; ?>
                            </a>
                        </div>
                        <div class="px-2 mt-2 d-flex justify-content-between" style="font-size: 12px; color: black; font-weight:bold;">
                            <div>Bids: <?php echo $item->BidCount; ?></div>
                            <div class="text-end">$<?php echo $item->CurrentPrice; ?></div>
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
                    <H1 style="color: black;">Popular eBay Items</H1>
                    <p>You will be redirected to an ebay listing</p>
                </div>
            </div>
            <div class="row">
                <?php 
                foreach($popular as $item){ 
                ?>
                <div class="d-flex align-items-start col-md-3 col-sm-6 mb-3 text-center">
                    <div>
                        <div style="height: 120px; min-height: 120px; max-height: 120px;">
                            <a href="<?php echo $item->ViewItemURL ?>?mkcid=1&mkrid=711-53200-19255-0&siteid=0&campid=5339081621&customid=&toolid=10001&mkevt=1" target="_blank">
                                <img style="margin-top: auto; margin-bottom: auto; height: 100%;" src="<?php echo $item->GalleryURL ?>">
                            </a>
                        </div>
                        <div class="itemTitle">
                            <a href="<?php echo $item->ViewItemURL ?>?mkcid=1&mkrid=711-53200-19255-0&siteid=0&campid=5339081621&customid=&toolid=10001&mkevt=1" target="_blank">
                                <?php echo $item->Title; ?>
                            </a>
                        </div>
                        <div class="px-2 mt-2 d-flex justify-content-between" style="font-size: 12px; color: black; font-weight:bold;">
                            <div>Watchers: <?php echo $item->WatchCount; ?></div>
                            <div class="text-end">$<?php echo $item->CurrentPrice; ?></div>
                        </div>
                    </div>
                </div>
                <?php 
                }
                ?>
            </div>
        </div>
    </div>
</div>