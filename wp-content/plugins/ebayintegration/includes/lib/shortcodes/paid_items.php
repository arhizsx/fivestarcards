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
                <th>Buyer</th>
                <th>Payment Timestamp</th>
                <th>Price Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($ebay as $item){ 
                if( $item->transaction != "Not Sold" ){
                    $data = json_decode($item->transaction, true);
                    $sold_amount = $data["Transaction"]["MonetaryDetails"]["PaymentAmount"];
                    $buyer = $data["Transaction"]["MonetaryDetails"]["Payer"];
                    $payment_time = $data["Transaction"]["MonetaryDetails"]["PaymentTime"];
            ?>
            <tr>
                <td></td>
                <td></td>
                <td><?php echo $buyer ; ?></td>
                <td><?php echo $payment_time ; ?></td>
                <td><?php echo $sold_amount ; ?></td>
            </tr>
            <?php 
                }
            } 
            ?>
        </tbody>
    </table>
</div>