<?php 

global $wpdb;

$maxpage = 500;

if( isset( $_GET['i'] ) ){
    $multiplier = $_GET['i'];
    $page = $_GET['i'] - 1;

} else {
    $multiplier = 0;
    $page = 1;
}

$skus = get_user_meta( get_current_user_id(), "sku", true );		

$array = implode("','",$skus);

$sql = "SELECT * FROM ebay WHERE sku IN ('" . $array . "')";
$ebay = $this->wpdb->get_results ( $sql );

print_r($sql);

print_r($ebay);

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
<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_paid">
        <thead>
            <tr>
                <th>Item</th>
                <th>Cancel ID</th>
                <th>Request Date</th>
                <th>Close Date</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if( count($ebay) > 0 ){

                $ctr = 0;

                foreach($ebay as $item){ 
                    if( $item->transaction != "Not Sold" ){


                        $transaction = json_decode($item->transaction, true);
                        $data = json_decode($item->data, true);
                        $ctr++;

                        if( array_key_exists("Item", $data) ){
                            $title = $data["Item"]["Title"];
                            $url = $data["Item"]['ListingDetails']['ViewItemURL'];
                        } else {
                            $title = $data["Title"];
                            $url = $data['ListingDetails']['ViewItemURL'];
                        }

                        $cancel = json_decode($item->cancellation, true);

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
                    <?php $listing = $data["Item"]["ListingType"] == "Chinese" ? "Auction" : $data["Item"]["ListingType"]; ?>
                    <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                    
                    <div class="item_id text-small">ID: <?php echo $item->id ?></div>                                        
                </td>    
                <td>
                    <?php 
                        print_r($cancel["cancelId"]);
                    ?>
                </td>    
                <td>
                    <?php 
                        print_r($cancel["cancelRequestDate"]["value"]);
                    ?>
                </td>    
                <td>
                    <?php 
                        print_r($cancel["cancelCloseDate"]["value"]);
                    ?>
                </td>    
                <td>
                    <?php 
                        print_r($cancel["cancelStatus"]);
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