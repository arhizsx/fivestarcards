<?php 

global $wpdb;

$skus = get_user_meta( get_current_user_id(), "sku", true );		

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  view_auction 
"
);


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
    LIMIT 12;    
" 
);

?>
<style>
    .text-small {
        font-size: .7em !important;
    }
</style>
<div class="d-flex flex-row-reverse mb-3">
    <input class="btn pl-2 search_box" style="text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_auction">
</div>
<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_auction">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-end">Bids</th>
                <th class="text-end">Current Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 

                $available = 0;
                if($skus != null){                    
                    foreach($ebay as $item){ 
                        if( in_array( $item->sku, $skus ) ){
                            $available++;
                        }
                    }
                }

                if( $available > 0 && $skus != null){
            ?>
                    <?php 
                    foreach($ebay as $item){ 
                        if( in_array( $item->sku, $skus ) ){
                            $i++;
                    ?>
                    <tr>
                        <td>
                            <div class="title">
                                <span class='pe-2'><strong><?php echo $i ?></strong></span>
                                <a href="<?php echo $item->ViewItemURL ?>?mkcid=1&mkrid=711-53200-19255-0&siteid=0&campid=5339081621&customid=&toolid=10001&mkevt=1" target="_blank">
                                <?php echo  $item->Title; ?>
                                </a>
                            </div>
                            <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                            <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                            <div class="item_id text-small">ID: <?php echo $item->id ?></div>                    
                        </td>
                        <td class="text-end">
                            <?php echo $item->BidCount?>
                        </td>
                        <td class="text-end">$<?php 
                            echo number_format(( $item->CurrentPrice), 2, '.', ',');
                        ?></td>
                    </tr>
                    <?php 
                        }
                    }
                    ?>
            <?php 
                } else {
            ?>
                <tr>
                    <td colspan="3" class="text-center p-5">Empty</td>
                </tr>
            <?php                     
                }
            ?>
        </tbody>
    </table>    
</div>
<div class="row">
    <div class="col">
        <H1>Hot Auctions</H1>
    </div>
</div>
<div class="row">
    <?php 
    foreach($hot as $item){ 
    ?>
    <div class="col-md-3 mb-3 text-center">
        <img style="min-height: 80px;" src="<?php echo $item->GalleryURL ?>">
        <div><?php echo $item->CurrentPrice; ?></div>
        <div><?php echo $item->Title; ?></div>
    </div>
    <?php 
    }
    ?>
</div>