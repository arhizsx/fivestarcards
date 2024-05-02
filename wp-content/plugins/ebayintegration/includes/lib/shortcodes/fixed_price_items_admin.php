<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'active'
"  
);


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
        <i class="fa-brands fa-ebay fa-2xl"></i> FIXED PRICE
    </div>
    <div>
        <select class="user_list_select form-control">
            <?php 
                foreach($users as $user) {
                    $skus = get_user_meta( $user->ID, "sku", true );
            ?>
            <option><?php  echo $user->display_name ?></option>
            <?php                    
                }
            ?>
        </select>
        <input class="btn pl-2 search_box" style="text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_auction">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_fixed_price">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-end">Buy Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if( count($ebay) > 0 ){

                foreach($ebay as $item){ 
                    $data = json_decode($item->data, true);

                    if( $data["ListingType"] != "Chinese"){

            ?>
            <tr>
                <td>
                    <div class="title">
                        <a href="<?php echo $data['ListingDetails']['ViewItemURL'] ?>" target="_blank">
                        <?php echo $data["Title"]; ?>
                        </a>
                    </div>
                    <div class="sku text-small">SKU: <?php echo $item->sku ?></div>
                    <div class="item_id text-small">Item ID: <?php echo $item->item_id ?></div>                    
                </td>
                <td class="text-end">$<?php 
                echo number_format(( $data["SellingStatus"]["CurrentPrice"]), 2, '.', ',');
                ?></td>
            </tr>
            <?php 
                    }
                }

            } 
            else {
            ?>
            <tr>
                <td colspan="2" class="text-center p-5">
                    No Item
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
</div>