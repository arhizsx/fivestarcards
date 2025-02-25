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

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM ebay
WHERE status = 'Cancelled'
ORDER BY JSON_UNQUOTE(JSON_EXTRACT(data, '$.Item.ListingDetails.StartTime')) ASC
" );

$ebay_count = $this->wpdb->get_results ( "
SELECT COUNT(id) as total 
FROM  ebay
where status = 'Cancelled'
" 
);

$total_pages = ceil($ebay_count[0]->total / $maxpage ) ;


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
    <div class="d-flex justify-content-between mb-3">
        <div>
        Page: 
        <select class="ps-2 mobile_tab_select">
            <?php 
            for( $i = 1; $i <= $total_pages; $i++ ){
            ?>
            <option value="/administrator/consignment/?mode=ebay&type=paid_out&i=<?php echo $i ?>" <?php echo $_GET["i"] == $i ? "selected" : ""; ?>><?php echo $i ?></option>
            <?php    
            }
            ?>
            <option></option>
        </select>
        </div>
        <input class="btn pl-2 search_box" style="margin-left: 15px; text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_paid">
    </div>

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
                <td colspan="2" class="text-center p-5">
                    No Items
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
</div>