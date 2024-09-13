<?php 

global $wpdb;

$skus = get_user_meta( get_current_user_id(), "sku", true );		

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  view_auction 
"
);

print_r( $skus );

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

                        if( $item["ListingType"] == "Chinese"){

                            print_r( $item->sku );
                            echo "<br>";
    
                            if( in_array( $item->sku, $skus ) ){
                                $available++;
                            }
                        }
                    }
                }

                print_r( $available );

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
                                <a href="<?php echo $item->ViewItemURL ?>" target="_blank">
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