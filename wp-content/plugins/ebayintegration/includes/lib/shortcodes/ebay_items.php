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
        font-weight: 500;
        margin-top: 10px;
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
    .ebay-price {
        font-size: 1.2em;
        font-weight: bold;
        text-align: right;
    }
</style>

<div class="mt-4 p-0">
    <div class="row">
        <div class="col-12">
            <H1 style="color: black;">eBay Items</H1>            
        </div>
    </div>
    <div class="row">
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th class="text-end">Current Price</th>
                        <th class="text-end">Days Left</th>
                    </tr>
                </thead>
                <tbody> 
            <?php 
                foreach($results as $result){

                    $data = json_decode($result->data);

            ?>
                <tr class=".ebay-item" data-item_id="<?php echo $data->ItemID ?>">
                    <td><?php echo $data->Title ?></td>
                    <td class="text-end"><?php echo $data->SellingStatus->CurrentPrice ?></td>
                    <td class="text-end"><?php echo $data->SellingStatus->CurrentPrice ?></td>

                </tr>
            <?php 
                }
            ?>
                </tbody>
            </table>
    </div>
</div>

<script>

    var items;

$(document).ready(function(){

    var token = refreshAccessToken();


    $.when(token).done(function(response){

		console.log("Refreshed Access Token");

		if( response["token_type"] == "User Access Token" ){

            items = $(document).find("tr.ebay-item");

            console.log(items);

            $.each(items, function(k, v){


                var item_id = items.eq(k).attr("data-item_id");

                jQuery.ajax({
                    method: 'get',
                    url: "/wp-json/ebayintegration/v1/ajax",
                    data: {
                        action : "getItemInfo",
                        item_id : item_id,
                    },
                    success: function(resp){
                        console.log(resp);
                        var img = resp.data.Item.PictureDetails.PictureURL[0];
                        $(document).find(".ebay-item[data-item_id='" + item_id +"']").find("img").attr("src", img);

                    },
                    error: function(){
                    }
                });

            });

        }


    });


});

</script>