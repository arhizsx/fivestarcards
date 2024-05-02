<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'awaiting'
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
    <div class="d-flex justify-content-between mb-3">
        <select class="user_list_select form-control">
        <option value="">Filter by User</option>
        <?php 
            foreach($users as $user) {
        ?>
        <option value="<?php echo $user->ID; ?>"> <?php echo $user->display_name; ?></option>
        <?php                 
            }
        ?>
        </select>
        <input class="btn pl-2 search_box" style="margin-left: 15px; text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_sold">
    </div>

</div>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_sold">
        <thead>
            <tr>
                <th>Item</th>
                <th class="d-none d-md-table-cell">Timestamp</th>
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

                        if( in_array( $item->sku, $skus ) ){

            ?>
            <tr>
                <td>
                    <div class="title">
                        <a href="<?php echo $data['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                            <?php print_r( $data["Title"] ); ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <div class="item_id text-small">Listing Type: <?php echo $data["ListingType"] ?></div>                    
                </td>
                <td class="d-none d-md-table-cell"><?php echo $transaction["Transaction"]["MonetaryDetails"]["Payments"]["Payment"]["PaymentTime"] ?></td>
                <td class="text-end">$<?php echo number_format(( $transaction["Transaction"]["MonetaryDetails"]["Payments"]["Payment"]["PaymentAmount"]), 2, '.', ',');?></td>
            </tr>
            <?php 
                        }
                    }
                } 
            } 
            else {
            ?>
            <tr>
                <td colspan="3" class="text-center p-5">
                    No Items
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
</div>