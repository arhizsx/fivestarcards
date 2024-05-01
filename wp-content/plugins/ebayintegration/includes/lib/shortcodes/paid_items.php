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
                <td>
                    <div class="title"><?php print_r( $data["Title"] ); ?></div>
                    <div class="sku"><?php echo $item->sku ?></div>
                    <div class="item_id"><?php echo $item->item_id ?></div>
                </td>
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