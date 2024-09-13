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

$sql = "SELECT * FROM ebay WHERE status = 'Cancelled' AND sku IN ('" . $array . "')";
$ebay = $this->wpdb->get_results ( $sql );

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
    </table>
</div>