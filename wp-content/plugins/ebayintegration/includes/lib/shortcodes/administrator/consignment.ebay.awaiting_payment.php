<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'SoldListAwaiting'
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
<div class="d-flex flex-row-reverse mb-3">
    <div class="d-flex justify-content-between mb-3">
        <input class="btn pl-2 search_box" style="margin-left: 15px; text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_paid">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_paid">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if( count($ebay) > 0 ){
                $ctr = 0;
                foreach($ebay as $item){ 
                    
                    $data = json_decode($item->data, true);
                    $ctr++;

                    if( in_array("Item", $data) ){
                        $url = $data["Item"]['ListingDetails']['ViewItemURL'];
                        $title = $data["Item"]["Title"];
                        $listing_type = $data["Item"]["ListingType"];
                        $current_price = $data["Item"]["SellingStatus"]["CurrentPrice"];
                    } else {
                        $url = $data['ListingDetails']['ViewItemURL'];
                        $title = $data["Title"];
                        $listing_type = $data["ListingType"];
                        $current_price = $data["SellingStatus"]["CurrentPrice"];
                    }
            ?>
            <tr>
                <td>
                    <div class="title">
                        <?php echo $ctr; ?>&nbsp;
                        <a href="<?php echo $url ?>" target="_blank">
                            <?php print_r( $title ); ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <?php $listing = $listing_type == "Chinese" ? "Auction" : $listing_type; ?>
                    <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                    
                    <div class="item_id text-small">ID: <?php echo $item->id ?></div>                    
                    
                </td>
                <td class="text-end">
                    $<?php 
                    echo number_format(( $current_price ), 2, '.', ',');
                    ?>
                </td>
            </tr>
            <?php
                } 
            } 
            else {
            ?>
            <tr>
                <td colspan="2" class="text-center p-5">
                    No Items
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
</div>