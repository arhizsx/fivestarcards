<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'PaidOut'
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
    <input class="btn pl-2 search_box" style="text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_paid">
</div>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_paid">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-end">Amount Paid</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if( count($ebay) > 0 ){
                foreach($ebay as $item){ 
                    if( $item->transaction != "Not Sold" ){
                        $transaction = json_decode($item->transaction, true);
                        $data = json_decode($item->data, true);

                        if( in_array( $item->sku, $skus ) ){
                            $ctr++;

            ?>
            <tr>
            <td>
                    <div class="title">
                        <?php echo $ctr;  ?>&nbsp;
                        <a href="<?php echo $data["Item"]['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                            <?php print_r( $data["Item"]["Title"] ); ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <?php $listing = $data["Item"]["ListingType"] == "Chinese" ? "Auction" : $data["Item"]["ListingType"]; ?>
                    <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                    
                    <div class="item_id text-small">ID: <?php echo $item->id ?></div>                    
                    
                </td>
                <td class="text-end">
                    $<?php 
                    echo number_format(( $data["TransactionPrice"]), 2, '.', ',');
                    ?>
                </td>
            </tr>
            <?php
                        } 
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