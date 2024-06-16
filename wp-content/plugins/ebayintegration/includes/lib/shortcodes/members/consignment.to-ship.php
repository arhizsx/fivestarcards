<?php 

global $wpdb;

$consignment = $this->wpdb->get_results ( "
SELECT * 
FROM consignment
where user_id = " . get_current_user_id() . " 
and status = 'shipped'
order by order_id desc, id desc
"
);

?>
<div class="row">
    <div class="col">
        <a class="btn btn-pill btn-sm mb-2 btn-primary" href="/my-account/consignment/?mode=listed&type=auction">Cards</a>
        <a class="btn btn-pill btn-sm mb-2 btn-secodary" href="/my-account/consignment/?mode=listed&type=fixed_price">Orders</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Year</th>
                <th>Brand</th>
                <th>Player Name</th>
                <th>Card Number</th>
                <th>Attribute S/N</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if( count( $consignment ) == 0 ){
            ?>
            <tr>
                <td colspan="6" class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $consignment as $card ){
                        $data = json_decode( $card->data, true );
            ?>
                <tr>
                    <td><?php echo $card->order_id + 1000; ?></td>
                    <td><?php echo $data["year"] ?></td>
                    <td><?php echo $data["brand"] ?></td>
                    <td><?php echo $data["player_name"] ?></td>
                    <td class='text-end'><?php echo $data["card_number"] ?></td>
                    <td class='text-end'><?php echo $data["attribute_sn"] ?></td>
                </tr>
            <?php 
                    }
                }
            ?>

        </tbody>
    </table>
</div>