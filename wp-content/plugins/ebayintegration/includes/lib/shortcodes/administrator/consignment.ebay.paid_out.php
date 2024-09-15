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

