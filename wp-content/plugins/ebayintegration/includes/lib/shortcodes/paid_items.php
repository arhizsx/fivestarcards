<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'paid'
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
                <th class="text-end">Price Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if( count($ebay) > 0 ){
                foreach($ebay as $item){ 
                    if( $item->transaction != "Not Sold" ){
                        $transaction = json_decode($item->transaction, true);
                        $data = json_decode($item->data, true);
            ?>
            <tr>
                <td>
                    <div class="title"><?php print_r( $data["Title"] ); ?></div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <div class="item_id text-small">Listing Type: <?php echo $data["ListingType"] ?></div>

                    
                </td>
                <td class="text-end">$<?php 
                echo number_format(( $transaction["Transaction"]["MonetaryDetails"]["Payments"]["Payment"]["PaymentAmount"]), 2, '.', ',');
                ?></td>
            </tr>
            <?php 
                    }
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