<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'ScheduledList'
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
    <input class="btn pl-2 search_box" style="text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_sold">
</div>
<?php 
    $available = 0;
    if($skus != null){
        foreach($ebay as $item){ 
            if( $item->transaction != "Not Sold" ){
                if( in_array( $item->sku, $skus ) ){
                    $available++;
                }
            }
        }
    }
?>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_sold">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-start">Listing Start</th>
                <th class="text-end">Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 

            if( $available > 0 && $skus != null){
                foreach($ebay as $item){ 
                    if( $item->transaction != "Not Sold" ){
                        $data = json_decode($item->data, true);

                        if( in_array( $item->sku, $skus ) ){
                            $ctr++;

                            $date = new DateTime($data["ListingDetails"]["StartTime"], new DateTimeZone('UTC'));

                            $date->setTimezone(new DateTimeZone('America/Chicago'));
                            
                            // Format the output
                            $sched_date =  $date->format('Y-m-d H:i:s T');                            
            ?>
            <tr>
                <td>
                    <div class="title">
                        <strong><?php echo $ctr;  ?></strong>&nbsp;
                        <a href="<?php echo $data['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                            <?php print_r( $data["Title"] ); ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <?php $listing = $data["ListingType"] == "Chinese" ? "Auction" : $data["ListingType"]; ?>
                    <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                    
                </td>
                <td class="text-start">
                    <?php echo $sched_date; ?>
                </td>
                <td class="text-end">
                    $<?php echo number_format(( $data["SellingStatus"]["CurrentPrice"]), 2, '.', ','); ?>
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
                <td colspan="3" class="text-center p-5">
                    Empty
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
</div>