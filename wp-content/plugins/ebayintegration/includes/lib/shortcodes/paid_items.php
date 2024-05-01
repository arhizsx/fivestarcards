<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
where status == 'completed'
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
                if($item->transaction == "Not Sold"){
                } else {
                    $trans = json_decode( $item->transaction, true );
                }
            ?>
            <tr>
                <td></td>
                <td><?php print_r($item->transaction) ?></td>
                <td><?php echo $trans["Transaction"]["AmountPaid"] ?></td>
            </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>
</div>