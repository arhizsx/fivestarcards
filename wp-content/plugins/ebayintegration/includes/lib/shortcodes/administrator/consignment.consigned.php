<style>
.table td.fit, 
.table th.fit {
   white-space: nowrap;
   width: 1%;
}    
</style>

<?php 

global $wpdb;

if( ! isset( $_GET['type'] ) ){

    $consignment = $this->wpdb->get_results ( "
		SELECT 
        consignment.*,
        wp_users.user_email,
        wp_users.display_name
        FROM consignment
        	INNER JOIN wp_users
            ON consignment.user_id = wp_users.ID
        where status = 'received'
        order by order_id desc, consignment.id desc;
    ");

    $show = "cards";
    $btn_cards = 'btn-primary';
    $btn_orders = 'btn-secondary';

} else {

    $orders = $this->wpdb->get_results ( "
        SELECT * 
        FROM consignment_orders
        where status = 'received'
        order by id desc
        "
    );

    $show = "orders";
    $btn_cards = 'btn-secondary';
    $btn_orders = 'btn-primary';
}
?>

<div class="row">
    <div class="col">
        <a class="btn btn-pill btn-sm mb-2 <?php echo $btn_cards; ?>" href="/administrator/consignment/?mode=consigned">Cards</a>
        <a class="btn btn-pill btn-sm mb-2 <?php echo $btn_orders; ?>" href="/administrator/consignment/?mode=consigned&type=orders">Orders</a>
    </div>
</div>


<?php 
if( $show == "cards" ){
?>
<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Order ID</th>
                <th>Year</th>
                <th>Brand</th>
                <th>Player Name</th>
                <th>Card Number</th>
                <th>Attribute S/N</th>
                <th class="fit text-end">Action</th>
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
                    foreach( $consignment as $card ) {
                        $data = json_decode( $card->data, true );
            ?>
            <tr>
                <td>
                    <div><?php echo $card->display_name ?></div>
                    <div class="small"><?php echo $card->user_email ?></div>
                </td>
                <td><?php echo $card->order_id + 1000 ?></td>
                <td><?php echo $data["year"] ?></td>
                <td><?php echo $data["brand"] ?></td>
                <td><?php echo $data["player_name"] ?></td>
                <td><?php echo $data["card_number"] ?></td>
                <td><?php echo $data["attribute_sn"] ?></td>
                <td class="fit">
                    <a class="btn btn-pill btn-sm btn-danger ebayintegration-btn" data-action="confirmConsignedCardNotAvailable" data-id="<?php echo $card->id ?>">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                    <a class="btn btn-pill btn-sm btn-success ebayintegration-btn" data-action="confirmReceiveConsignedCard" data-id="<?php echo $card->id ?>">
                        <i class="fa-solid fa-check"></i>
                    </a>
                </td>
            </tr>
            <?php 
                    }
                }
            ?>
        </tbody>
    </table>
</div>
<?php     
} else {
?>
<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Order ID</th>
                <th>Carrier</th>
                <th>Shipped By</th>
                <th>Tracking Number</th>
                <th>Shipping Date</th>
                <th>Status</th>
                <th class="fit">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if( count( $orders ) == 0 ){
            ?>
            <tr>
                <td colspan="8" class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $orders as $order ) {
                        $data = json_decode( $order->data, true );
            ?>
                <tr>
                    <td>-</td>
                    <td><?php echo $order->id + 1000; ?></td>
                    <td><?php echo $data["carrier"]; ?></td>
                    <td><?php echo $data["shipped_by"]; ?></td>
                    <td><?php echo $data["tracking_number"]; ?></td>
                    <td><?php echo $data["shipping_date"]; ?></td>
                    <td><?php echo strtoupper($order->status); ?></td>
                    <td class="fit">
                        <a class="btn btn-pill btn-sm btn-success ebayintegration-btn" data-action="confirmReceiveConsignedCardAll" data-id="<?php echo $order->id; ?>">
                            <i class="fa-solid fa-check me-2"></i>All
                        </a>
                    </td>
                </tr>
            <?php
                    }
                } 
            ?>
        </tbody>
    </table>
</div>
<?php     
}
?>
