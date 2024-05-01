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
            ?>
            <tr>
                <td></td>
                <td><?php echo $item->transaction["Transaction"]["AmountSold"]  ; ?></td>
                <td></td>
            </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>
</div>