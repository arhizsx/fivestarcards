<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  view_fixed_price
"  
);

$skus = get_user_meta( get_current_user_id(), "sku", true );		


?>
<style>
    .text-small {
        font-size: .7em !important;
    }
</style>
<div class="d-flex flex-row-reverse mb-3">
    <input class="btn pl-2 search_box" style="text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_fixed_price">
</div>
<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_fixed_price">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-end">Sold</th>
                <th class="text-end">Avlb</th>
                <th class="text-end">Watchers</th>
                <th class="text-end">Buy Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 

            $available = 0;

            if($skus != null){
                foreach($ebay as $item){ 
                    $data = json_decode($item->data, true);
                    if( $data["ListingType"] != "Chinese"){
                        if( in_array( $item->sku, $skus ) ){
                            $available++;
                        }
                    }
                }
            }

            if( $available > 0 && $skus != null){

                foreach($ebay as $item){                     
                    
                    if( in_array( $item->sku, $skus ) ){
                        $i++;
            ?>
            <tr>
                <td>
                    <div class="title">
                        <span class='pe-2'><strong><?php echo $i ?></strong></span>
                        <a href="<?php echo $item->ViewItemURL ?>" target="_blank">
                            <?php echo $item->Title; ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>                    
                    <div class="item_id text-small">ID: <?php echo $item->id ?></div>                    
                </td>
                <td class="text-end">
                <?php echo $item->QuantitySold; ?>
                </td>
                <td class="text-end">
                    <?php echo $item->QuantityAvailable; ?>
                </td>
                <td class="text-end">
                    <?php echo $item->WatchCount; ?>
                </td>
                <td class="text-end">$<?php 
                echo number_format(( $item->CurrentPrice), 2, '.', ',');
                ?></td>
            </tr>
            <?php 
                    }
                }

            } 
            else {
            ?>
            <tr>
                <td colspan="5" class="text-center p-5">
                    Empty
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
</div>