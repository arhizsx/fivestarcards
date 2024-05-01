<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status = 'completed'
" 
);

?>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>SKU</th>
                <th>Buyer</th>
                <th class="text-end">Price Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($ebay as $item){ 
                if( $item->transaction != "Not Sold" ){
                    $data = json_decode($item->transaction, true);
            ?>
            <tr>
                <td></td>
                <td><?php echo $item->sku ?></td>
                <td><?php print_r($data["Transaction"]["MonetaryDetails"]["Payments"]["Payment"]["Payer"])  ; ?></td>
                <td class="text-end"><?php print_r($data["Transaction"]["MonetaryDetails"]["Payments"]["Payment"]["PaymentAmount"])  ; ?></td>
            </tr>
            <?php 
                }
            } 
            ?>
        </tbody>
    </table>
</div>