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

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-end">Current Price</th>
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
                    <div class="title"><?php echo $data["Title"]; ?>< /div>
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