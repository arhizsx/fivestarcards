<?php 

global $wpdb;

$skus = get_user_meta( get_current_user_id(), "sku", true );		

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  view_auction 
WHERE ListingType = 'Chinese'
"
);


print_r( $skus );

$available = count($ebay);

?>
<style>
    .text-small {
        font-size: .7em !important;
    }
</style>
<div class="d-flex flex-row-reverse mb-3">
    <input class="btn pl-2 search_box" style="text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_auction">
</div>
