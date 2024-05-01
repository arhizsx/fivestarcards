<?php 

global $wpdb;

$ebay = $this->wpdb->get_results ( "
SELECT * 
FROM  ebay
WHERE status <> 'completed'
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
                <td><?php print_r( $item ); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>