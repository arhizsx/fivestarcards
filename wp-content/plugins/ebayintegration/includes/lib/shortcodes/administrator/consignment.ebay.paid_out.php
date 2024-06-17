<?php 

global $wpdb;

$maxpage = 200;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'PaidOut'
ORDER BY id DESC
LIMIT " . $maxpage . " OFFSET 0
" 
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
        <select class="p-2">
            <?php 
            for( $i = 1; $i <= $total_pages; $i++ ){
            ?>
            <option value="/administrator/consignment/?mode=ebay&type=paid_out&i=<?php echo $i ?>"><?php echo $i ?></option>
            <?php    
            }
            ?>
            <option></option>
        </select>

        <input class="btn pl-2 search_box" style="margin-left: 15px; text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_paid">
    </div>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_paid">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-end">Amount Paid</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if( count($ebay) > 0 ){
                foreach($ebay as $item){ 
                    if( $item->transaction != "Not Sold" ){
                        $transaction = json_decode($item->transaction, true);
                        $data = json_decode($item->data, true);
                        $ctr++;

            ?>
            <tr>
            <td>
                    <div class="title">
                        <strong><?php echo $ctr;  ?></strong>&nbsp;
                        <a href="<?php echo $data["Item"]['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                            <?php print_r( $data["Item"]["Title"] ); ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>
                    <?php $listing = $data["Item"]["ListingType"] == "Chinese" ? "Auction" : $data["Item"]["ListingType"]; ?>
                    <div class="item_id text-small">Listing Type: <?php echo $listing; ?></div>                    
                    <div class="item_id text-small">ID: <?php echo $item->id ?></div>                    

                    
                </td>                
                <td class="text-end">
                    $<?php 
                    echo number_format(( $data["TransactionPrice"]), 2, '.', ',');
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