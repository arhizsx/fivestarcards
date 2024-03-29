<?php
global $wpdb;



$skus = get_user_meta( get_current_user_id(), "sku", true );

$in = "(";
foreach($skus as $sku){
    $in = $in . '"' . $sku . '",';
}

$in = rtrim($in, ',');

$in = $in . ")";

$results = $wpdb->get_results("
    SELECT * FROM ebay WHERE sku IN " . $in . "
");

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
            <?php 
                foreach($results as $result){

                    $data = json_decode($result->data);


                    echo "<div class='col-3 border mb-3' style='min-height: 350px;'>";
                    echo "<img src='' style='min-height: 250px;' />";
                    echo "<div class='title'>";
                        print_r($data->Title);
                        // print_r($result->data["ItemID"]);
                        echo "</div>";
                    echo "</div>";
                }
            ?>

    </div>
</div>