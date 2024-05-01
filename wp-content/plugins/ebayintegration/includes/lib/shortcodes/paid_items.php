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
                <th>Item ID</th>
                <th>Buyer</th>
                <th class="text-end">Price Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($ebay as $item){ 
                if( $item->transaction != "Not Sold" ){
                    $transaction = json_decode($item->transaction, true);
                    $data = json_decode($item->data, true);

                    if( $data["Transaction"]["Status"]["CompleteStatus"] == "Incomplete" ){

                        $this->wpdb->update(
                            "ebay", 
                            array(
                                "status" => "incomplete"
                            ),
                            array(
                                "item_id" => $item->item_id
                            )
                        );
        
                    }
            ?>
            <tr>
                <td><?php print_r( $data ); ?></td>
                <td><?php echo $item->sku ?></td>
                <td><?php echo $item->item_id ?></td>
                <td><?php print_r($transaction["Transaction"]["MonetaryDetails"]["Payments"]["Payment"]["Payer"])  ; ?></td>
                <td class="text-end"><?php print_r($transaction["Transaction"]["MonetaryDetails"]["Payments"]["Payment"]["PaymentAmount"])  ; ?></td>
            </tr>
            <?php 
                }
            } 
            ?>
        </tbody>
    </table>
</div>