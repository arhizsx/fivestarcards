<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
ORDER BY sku ASC
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
            <?php foreach($ebay as $item){ ?>
            <tr>
                <td></td>
                <td></td>
                <td><?php echo $item->transaction["transaction"]["AmountPaid"] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>