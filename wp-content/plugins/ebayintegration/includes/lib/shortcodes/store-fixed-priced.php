<?php 
$popular = $this->wpdb->get_results ( "
    SELECT * 
    FROM view_fixed_price
    ORDER BY WatchCount DESC
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
                    <H1 style="color: black; margin-bottom: 0px;">Popular eBay Items</H1>
                </div>
            </div>
            <div class="row">
                <?php 
                foreach($popular as $item){ 
                ?>
                <div class="d-flex align-items-start col-md-3 col-sm-6 mb-3 text-center">
                    <div>
                        <div style="height: 200px; min-height: 200px; max-height: 200px;">
                            <a href="<?php echo $item->ViewItemURL ?>?mkcid=1&mkrid=711-53200-19255-0&siteid=0&campid=5339081621&customid=&toolid=10001&mkevt=1" target="_blank">
                                <img style="margin-top: auto; margin-bottom: auto; height: 100%;" src="<?php echo str_replace("s-l140.webp","s-l600.webp", $item->GalleryURL) ?>">
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