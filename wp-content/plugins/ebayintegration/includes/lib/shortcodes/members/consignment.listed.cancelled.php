<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'Cancelled'
ORDER BY id DESC
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
<?php 
    $available = 0;
    if($skus != null){
        foreach($ebay as $item){ 
            $data = json_decode($item->data, true);
            if( in_array( $item->sku, $skus ) ){
                $available++;
            }
        }
    }
?>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_paid">
        <thead>
            <tr>
                <th width="60%">Item</th>
                <th>Cancel ID</th>
                <th>Request Date</th>
                <th>Close Date</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if( $available > 0 && $skus != null ){
                foreach($ebay as $item){ 
                        $data = json_decode($item->data, true);

                        if( in_array( $item->sku, $skus ) ){

                            $ctr++;

            ?>
            <tr>
                <td>
                    <div class="title">
                        <strong><?php echo $ctr;  ?></strong>&nbsp;
                        <a href="<?php echo $data["Item"]['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                            <?php print_r( $data["Item"]["Title"] ); ?>
                        </a>
                    </div> 
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <?php $listing = $data["Item"]["ListingType"] == "Chinese" ? "Auction" : $data["ListingType"]; ?>
                    <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                                        
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
            </tr>
            <?php
                    }
                } 
            } 
            else {
            ?>
            <tr>
                <td colspan="5" class="text-center p-5">
                    No Items
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
</div>