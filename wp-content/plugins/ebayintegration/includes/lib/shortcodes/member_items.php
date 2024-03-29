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
    .title {
        font-size: 1em;
    }
    .sku {
        font-size: .8em;
    }
    .ebay-img {
        min-height: 350px;
        max-width: 200px;
        object-fit: contain;
    }
    .ebay-img-box {
        text-align: center;
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


                    echo "<div class='col-3 border mb-3' data-item_id='" . $data->ItemID . "'>";
                        echo "<div class='ebay-img-box'>";
                        echo "<img class='ebay-img' src='/wp-content/uploads/2023/09/5-star-cards-logo-1.png'>";
                        echo "</div>";
                        echo "<div class='title'>" . $data->Title . "</div>";
                        echo "<div class='sku'>" . $data->SKU . "</div>";
                    echo "</div>";
                }
            ?>

    </div>
</div>