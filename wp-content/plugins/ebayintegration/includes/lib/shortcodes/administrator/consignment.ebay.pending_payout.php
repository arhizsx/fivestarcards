<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT *  
FROM  ebay
where status IN ('SoldListPaid', 'PaidOutQueued' )
ORDER BY id DESC
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
                <th>eBay Pay Date</th>
                <th class="text-end">Price Sold</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $ctr = 0;

            foreach($ebay as $item){ 

                $data = json_decode($item->data, true);
                    $ctr++;
            ?>
            <?php 
                if( $item->status == "PaidOutQueued" ) {
                    $setStyle = 'style="border: 2px solid black;"';
                } else {
                    $setStyle = "";
                }
            ?>

            <tr class="ebay_card_row" data-id="<?php echo $item->id ?>" <?php echo $setStyle; ?>>
                <td>
                    <div class="title">
                        <strong><?php echo $ctr;  ?></strong>&nbsp;
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
                <td class="">
                    <?php
                    $paid_time = explode("T",$data["PaidTime"]); 
                    echo $paid_time[0];
                    ?>
                </td>
                <td class="text-end">
                    $<?php 
                    echo number_format(( $data["TransactionPrice"]), 2, '.', ',');
                    ?>
                </td>
                <td class="text-end">
                    <button class="btn btn-primary btn-sm mb-2 ebayintegration-btn" data-action="consignmentPaidOut" data-id="<?php echo $item->id ?>">PAID</button>
                    <?php 
                    if( get_current_user_id() == 1 ){ 
                        if( $item->status == "PaidOutQueued" ){
                    ?>                            
                        <button class="btn btn-success btn-sm ms-2 mb-2 ebayintegration-btn" data-action="consignmentPaidOutRelease" data-id="<?php echo $item->id ?>">RELEASE</button>
                    <?php 
                        } else {
                    ?>
                        <button class="btn btn-dark btn-sm ms-2 mb-2 ebayintegration-btn" data-action="consignmentPaidOutQueue" data-id="<?php echo $item->id ?>">QUEUE</button>
                    <?php 
                        }
                    } 
                    ?>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>