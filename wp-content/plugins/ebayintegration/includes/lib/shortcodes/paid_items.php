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
            ?>
            <tr>
                <td></td>
                <td><?php print_r($item) ?></td>
                <td></td>
            </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>
</div>