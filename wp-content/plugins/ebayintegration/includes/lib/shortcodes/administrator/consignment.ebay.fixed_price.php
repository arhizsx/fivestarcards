<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'ActiveList'
"  
);


$args = array(
    'orderby'    => 'display_name',
    'order'      => 'ASC'
);

$users = get_users( $args );


?>
<style>
    .text-small {
        font-size: .7em !important;
    }
</style>
<div class="d-flex justify-content-between mb-3">
    <div>
        <i class="fa-brands fa-ebay fa-2xl"></i> FIXED PRICE
    </div>
    <div class="d-flex justify-content-between mb-3">
        <input class="btn pl-2 search_box" style="margin-left: 15px; text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_fixed_price">
    </div>
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
            if( count($ebay) > 0 ){

                $i = 0;

                foreach($ebay as $item){ 
                    $data = json_decode($item->data, true);

                    // if( array_key_exists("ListingType", $data) ){
                    //     if( $data["ListingType"] != "Chinese"){
                    //         $i++;
            ?>  
            <tr>
                <td>
                    <div class="title">
                        <span class='pe-2'><strong><?php echo $i ?></strong></span>
                        <a href="<?php echo $data['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                        <?php echo $data["Title"]; ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>                    
                    <div class="item_id text-small">ID: <?php echo $item->id ?></div>                    
                </td>
                <td class="text-end">
                <?php echo $data["SellingStatus"]["QuantitySold"] * 1; ?>
                </td>
                <td class="text-end">
                    <?php echo $data["QuantityAvailable"] * 1; ?>
                </td>
                <td class="text-end">
                    <?php echo $data["WatchCount"] * 1; ?>
                </td>
                <td class="text-end">$<?php 
                echo number_format(( $data["SellingStatus"]["CurrentPrice"]), 2, '.', ',');
                ?></td>
            </tr>
            <?php 
                    //     }
                    // }
                }

            } 
            else {
            ?>
            <tr>
                <td colspan="2" class="text-center p-5">
                    No Item
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
</div>