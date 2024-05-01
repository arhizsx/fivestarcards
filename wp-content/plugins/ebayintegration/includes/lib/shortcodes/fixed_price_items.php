<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'completed'
" 
);

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
                <th class="text-end">Buy Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($ebay as $item){ 
                $data = json_decode($item->data, true);

                if( $data["ListingType"] != "Chinese"){
            ?>
            <tr>
                <td>
                    <div class="title">
                        <a href="<?php echo $data['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                        <?php echo $data["Title"]; ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <div class="item_id text-small">Listing Type: <?php echo $data["ListingType"] ?></div>

                    
                </td>
                <td class="text-end">$<?php 
                echo number_format(( $data["SellingStatus"]["CurrentPrice"]), 2, '.', ',');
                ?></td>
            </tr>
            <?php 
                }
            }
            ?>
        </tbody>
    </table>
</div>