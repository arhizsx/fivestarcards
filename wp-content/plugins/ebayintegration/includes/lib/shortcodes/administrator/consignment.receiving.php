<style>
.table td.fit, 
.table th.fit {
   white-space: nowrap;
   width: 1%;
}    
</style>

<?php 

global $wpdb;

if( ! isset( $_GET['type'] )  || ( isset( $_GET['type'] ) && $_GET['type'] == 'orders' )  ){

    $orders = $this->wpdb->get_results ( "
        SELECT
            consignment_orders.*,
            wp_users.user_email,
            wp_users.display_name
        FROM consignment_orders
        	INNER JOIN wp_users
            ON consignment_orders.user_id = wp_users.ID
        where status = 'shipped'
        order by id desc
        "
    );

    $show = "orders";
    $btn_cards = 'btn-secondary';
    $btn_orders = 'btn-primary';


} else {

    $consignment = $this->wpdb->get_results ( "

		SELECT 
            consignment.*,
            wp_users.user_email,
            wp_users.display_name
        FROM consignment
        	INNER JOIN wp_users
            ON consignment.user_id = wp_users.ID
        where status = 'shipped'
        order by order_id desc, consignment.id desc;
        "
    );

    $show = "cards";
    $btn_cards = 'btn-primary';
    $btn_orders = 'btn-secondary';

}
?>

<div class="row">
    <div class="col">
        <a class="btn btn-pill btn-sm mb-2 <?php echo $btn_cards; ?>" href="/administrator/consignment?type=cards">Cards</a>
        <a class="btn btn-pill btn-sm mb-2 <?php echo $btn_orders; ?>" href="/administrator/consignment/?type=orders">Orders</a>
    </div>
</div>


<?php 
if( $show == "cards" ){
?>
<div class="table-responsive">
    <table class="table table-sm table-bordered" id="receiving_consignment">
        <thead>
            <tr>
                <th>User</th>
                <th>Order ID</th>
                <th>Year</th>
                <th>Brand</th>
                <th>Player Name</th>
                <th>Card Number</th>
                <th>Attribute S/N</th>
                <th class="fit text-end"></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if( count( $consignment ) == 0 ){
            ?>
            <tr>
                <td colspan="8" class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $consignment as $card ) {
                        $data = json_decode( $card->data, true );

                        print_r($data);
            ?>
            <tr class="consigned_item_row" data-id="<?php echo $card->id ?>">

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
                    <a class="btn btn-pill btn-sm btn-danger ebayintegration-btn" data-action="consignedCardNotReceived" data-id="<?php echo $card->id ?>" data-user_id="<?php echo get_current_user_id(); ?>">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                    <a class="btn btn-pill btn-sm btn-success ebayintegration-btn" data-action="confirmConsignedCardReceived" data-id="<?php echo $card->id ?>" data-user_id="<?php echo get_current_user_id(); ?>">
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
                <th class="fit"></th>
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
                <tr class="consigned_order_row" data-id="<?php echo $order->id ?>" data-type="oders">
                    <td>
                        <div><?php echo $order->display_name ?></div>
                        <div class="small"><?php echo $order->user_email ?></div>
                    </td>
                    <td><?php echo $order->id + 1000; ?></td>
                    <td><?php echo $data["carrier"]; ?></td>
                    <td><?php echo $data["shipped_by"]; ?></td>
                    <td><?php echo $data["tracking_number"]; ?></td>
                    <td><?php echo $data["shipping_date"]; ?></td>
                    <td><?php echo strtoupper($order->status); ?></td>
                    <td class="fit">
                        <a class="btn btn-pill btn-sm btn-primary ebayintegration-btn" data-action="" data-post_id="<?php echo $order->id ?>" data-user_id="<?php echo get_current_user_id(); ?>">
                            ...
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
