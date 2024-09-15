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
FROM  ebay
where status = 'PaidOut'
ORDER BY id DESC"
);

$ebay_count = $this->wpdb->get_results ( "
SELECT COUNT(id) as total 
FROM  ebay
where status = 'PaidOut'
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
    <input class="btn pl-2 search_box" style="margin-left: 15px; text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_paid">
</div>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_paid">
        <thead>
            <tr>
                <th>Item</th>
                <th>Paid Out Date</th>
                <th class="text-end">Amount Paid</th>
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

                        if( in_array("TransactionPrice", $data) ){
                            $current_price = $data["TransactionPrice"];
                        } 
                        else {
                            if( array_key_exists("Item", $data) ){
                                $current_price = $data["Item"]["SellingStatus"]["CurrentPrice"];
                            } else {
                                $current_price = $data["SellingStatus"]["CurrentPrice"];
                            }
                        }

                        if( array_key_exists("Item", $data) ){
                            $title = $data["Item"]["Title"];
                            $url = $data["Item"]['ListingDetails']['ViewItemURL'];
                        } else {
                            $title = $data["Title"];
                            $url = $data['ListingDetails']['ViewItemURL'];
                        }

            ?>
            <tr>
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