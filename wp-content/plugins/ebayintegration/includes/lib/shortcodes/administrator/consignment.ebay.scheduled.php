<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'ScheduledList'
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
                <th class="text-start">Listing Start</th>
                <th class="text-end">Price</th>
=            </tr>
        </thead>
        <tbody>
            <?php 
            if( count($ebay) > 0 ){
                $ctr = 0;
                foreach($ebay as $item){ 
                    
                    $data = json_decode($item->data, true);
                    $ctr++;

                    $date = new DateTime($data["ListingDetails"]["StartTime"], new DateTimeZone('UTC'));

                    $date->setTimezone(new DateTimeZone('America/Chicago'));
                    
                    // Format the output
                    $sched_date =  $date->format('Y-m-d H:i:s T');                            

            ?>
            <tr class="ebay_card_row" data-id="<?php echo $item->id ?>">
                <td>
                    <div class="title">
                        <?php echo $ctr; ?>&nbsp;
                        <a href="<?php echo $data['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                            <?php print_r( $data["Title"] ); ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <?php $listing = $data["ListingType"] == "Chinese" ? "Auction" : $data["ListingType"]; ?>
                    <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                    
                    <div class="item_id text-small">ID: <?php echo $item->id ?></div>                    
                    
                </td>
                <td class="text-start">
                    <?php echo $sched_date; ?>
                </td>
                <td class="text-end">
                    $<?php 
                    echo number_format(( $data["SellingStatus"]["CurrentPrice"]), 2, '.', ',');
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