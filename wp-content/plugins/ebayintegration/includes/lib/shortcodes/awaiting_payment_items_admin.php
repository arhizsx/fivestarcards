<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'SoldList'
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
        <input class="btn pl-2 search_box" style="margin-left: 15px; text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_paid">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_paid">
        <thead>
            <tr>
                <th>Item</th>
                <th>SellerPaidStatus</th>
                <th class="text-end">Price Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if( count($ebay) > 0 ){
                foreach($ebay as $item){ 
                    
                    $data = json_decode($item->data, true);


                    if( $data["SellerPaidStatus"] != "NotPaid" && $data["SellerPaidStatus"] != "MarkedAsPaid"  ){

            ?>
            <tr>
                <td>
                    <div class="title">
                        <a href="<?php echo $data["Item"]['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                            <?php print_r( $data["Item"]["Title"] ); ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <?php $listing = $data["Item"]["ListingType"] == "Chinese" ? "Auction" : $data["Item"]["ListingType"]; ?>
                    <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                    

                    
                </td>
                <td>
                    <?php echo $data["SellerPaidStatus"]; ?>
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