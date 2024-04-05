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
    tr {
        cursor: pointer;
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
                        <th class="text-center">Bids</th>
                        <th class="text-end">Current Price</th>
                        <th class="text-end">Days Left</th>
                    </tr>
                </thead>
                <tbody> 
            <?php 
                foreach($results as $result){

                    $data = json_decode($result->data);

            ?>
                <tr class="ebay-item" data-item_id="<?php echo $data->ItemID ?>" data-view_url="" data-view_image="">
                    <td><?php echo $data->Title ?></td>
                    <td class="text-end ebay-item-bids" data-item_id="<?php echo $data->ItemID ?>"></td>
                    <td class="text-center ebay-item-current_price" data-item_id="<?php echo $data->ItemID ?>"></td>
                    <td class="text-end ebay-item-days_left" data-item_id="<?php echo $data->ItemID ?>"></td>

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

            items = $(document).find(".ebay-item");


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
                        
                        var currentPrice = 0;
                        var daysLeft = "";
                        var endTime = "";
                        var startTime = "";
                        var viewURL = "";
                        var bids = "";

                        console.log(resp);

                        if( resp.data.Item.SellingStatus.QuantitySold == "0" ){

                            currentPrice = resp.data.Item.SellingStatus.CurrentPrice;
                            bids = resp.data.Item.SellingStatus.BidCount;
                            startTime = resp.data.Item.ListingDetails.StartTime;
                            endTime = resp.data.Item.ListingDetails.EndTime;
                            viewURL = resp.data.Item.ListingDetails.ViewItemURL;

                        } else {

                            currentPrice = "Sold";

                        }

                        var startDay = new Date(startTime);  
                        var endDay = new Date(endTime);  
                        
                        // Determine the time difference between two dates     
                        var millisBetween = startDay.getTime() - endDay.getTime();  
                        
                        // Determine the number of days between two dates  
                        var days = millisBetween / (1000 * 3600 * 24);  
                        
                        // Show the final number of days between dates     
                        var daysLeft =  Math.round(Math.abs(days));  

                        $(document).find(".ebay-item-bids[data-item_id='" + item_id + "']").text( bids);
                        $(document).find(".ebay-item-current_price[data-item_id='" + item_id + "']").text( "$" + currentPrice);
                        $(document).find(".ebay-item-days_left[data-item_id='" + item_id + "']").text(daysLeft);
                        $(document).find(".ebay-item[data-item_id='" + item_id + "']").attr("data-view_url", viewURL);


                    },
                    error: function(){
                    }
                });



            });


            

        }


    });


});

$(document).on("click", ".ebay-item", function(){
    console.log( url );

    var url = $(this).data("view_url");
    window.open(url);
    return false;

});

</script>