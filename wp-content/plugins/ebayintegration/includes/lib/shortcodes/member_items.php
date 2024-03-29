<?php
global $wpdb;



$skus = get_user_meta( get_current_user_id(), "sku", true );

echo implode( ",", $skus);

$in = "(";
foreach($skus as $sku){
    $in = $in . '"' . $sku . '",';
}

echo $in;

$results = $wpdb->get_results("
    SELECT * FROM ebay WHERE sku IN (" . implode( ",", $skus) . ")
");


print_r($results);

?>
<style>
    input {padding: 3px;}
    select {
        padding: 3px;
    }
    .ebayintegration-btn {
        text-decoration: none; 
    }
</style>

<div class="m-0 p-0">
    <div class="row">
        <div class="col-12">
            <H1 style="color: black;">eBay Items</H1>            
        </div>
    </div>
    <div class="row">
        <div class="col-12">
    
        </div>
    </div>
</div>