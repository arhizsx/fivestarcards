<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM ebay
WHERE status IN ('UnsoldList')
ORDER BY JSON_UNQUOTE(JSON_EXTRACT(data, '$.ListingDetails.StartTime')) DESC
" );

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
            ?>
            <?php

            if( array_key_exists("Item", $data)){
                $title = $data["Item"]["Title"];
                $url = $data["Item"]['ListingDetails']['ViewItemURL'];
                $listing = $data["Item"]["ListingType"] == "Chinese" ? "Auction" : $data["ListingType"];
            } else {
                $title = $data["Title"]; 
                $url = $data['ListingDetails']['ViewItemURL'];
                $listing = $data["ListingType"] == "Chinese" ? "Auction" : $data["ListingType"];
            }

            ?>

            <tr>
                <td>
                    <div class="title">
                        <strong><?php echo $ctr;  ?></strong>&nbsp;
                        <a href="<?php echo $url ?>" target="_blank">
                            <?php print_r( $title ); ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                    
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