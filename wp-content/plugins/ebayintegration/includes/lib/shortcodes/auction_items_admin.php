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
        <i class="fa-brands fa-ebay fa-2xl"></i> AUCTION
    </div>
    <div>
        <button class="btn btn-primary">Add Viewer</button>
        <select class="user_list_select" style=" padding-left: 10px; padding-bottom:8px; padding-top: 10px; margin-top: 10px;">
        <option value="">Filter by User</option>
        <?php 
            foreach($users as $user) {
        ?>
        <option value="<?php echo $user->ID; ?>"> <?php echo $user->display_name; ?></option>
        <?php                 
            }
        ?>
        </select>
        <input class="btn pl-2 search_box" style="margin-left: 15px; text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_auction">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_auction">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-end">Current Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($ebay as $item){ 
                $data = json_decode($item->data, true);

                if( $data["ListingType"] == "Chinese"){
                    
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
            ?>
        </tbody>
    </table>
</div>