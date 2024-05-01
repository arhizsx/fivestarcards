<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
" 
);

?>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>SKU</th>
                <th>Price Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($ebay as $item){ 

                if( $item->transaction == "Not Sold" ){
                    $sold_amount = "0.00";    
                } else {
                    $data = json_decode($item->transaction, true);
                    $sold_amount = $data["Transaction"]["MonetaryDetails"];
                }
            ?>
            <tr>
                <td></td>
                <td></td>
                <td><?php print_r( $sold_amount ); ?></td>
            </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>
</div>