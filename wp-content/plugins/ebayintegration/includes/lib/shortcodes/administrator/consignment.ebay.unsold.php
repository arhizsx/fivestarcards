<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status IN ('UnsoldList' )
ORDER BY id DESC
ORDER BY `json_unquote(json_extract(``wordpress``.``ebay``.``data``,'$.ListingDetails.StartTime'))` ASC
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
<div class="d-flex flex-row-reverse mb-3">
    <div class="d-flex justify-content-between mb-3">
        <input class="btn pl-2 search_box" style="margin-left: 15px; text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".search_table_paid">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-border table-striped table-sm table-hover search_table_paid">
        <thead>
            <tr>
                <th>Item</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $ctr = 0;

            foreach($ebay as $item){ 

                $data = json_decode($item->data, true);

                    
                    $ctr++;
            ?>
            <?php 
                if( $item->status == "PaidOutQueued" ) {
                    $setStyle = 'style="border: 2px solid black;"';
                } else {
                    $setStyle = "";
                }
            ?>

            <tr class="ebay_card_row" data-id="<?php echo $item->id ?>" <?php echo $setStyle; ?>>
                <td>
                    <div class="title">
                        <?php

                        if( array_key_exists("Item", $data)){
                            $title = $data["Item"]["Title"];
                            $url = $data["Item"]['ListingDetails']['ViewItemURL'];
                        } else {
                            $title = $data["Title"]; 
                            $url = $data['ListingDetails']['ViewItemURL'];
                        }

                        ?>

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
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>