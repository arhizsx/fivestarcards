<?php 

global $wpdb;

if( ! isset( $_GET['type'] ) ){

    $consignment = $this->wpdb->get_results ( "
        SELECT * 
        FROM consignment
        where user_id = " . get_current_user_id() . " 
        and status = 'shipped'
        order by order_id desc, id desc
        "
    );

    $show = "cards";
    $btn_cards = 'btn-primary';
    $btn_orders = 'btn-secondary';
} else {

    $orders = $this->wpdb->get_results ( "
        SELECT * 
        FROM consignment_orders
        where user_id = " . get_current_user_id() . " 
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
        <a class="btn btn-pill btn-sm mb-2 <?php echo $btn_cards; ?>" href="/my-account/consignment/?mode=to-ship">Cards</a>
        <a class="btn btn-pill btn-sm mb-2 <?php echo $btn_orders; ?>" href="/my-account/consignment/?mode=to-ship&type=orders">Orders</a>
    </div>
</div>
<div class="table-responsive  d-none d-lg-block">

<?php 
    if( $show == 'cards' ){
?>
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
<?php         
    } else {
?>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Carrier</th>
                <th>Shipped By</th>
                <th>Tracking Number</th>
                <th>Shipping Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if( count( $orders ) == 0 ) { 
            ?>
            <tr>
                <td colspan="5" class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
            } else {
                foreach( $orders as $order ){
                    $data = json_decode($order->data, true);
            ?>
            <tr>
                <td><?php echo $order->id + 1000; ?></td>
                <td><?php echo $data["carrier"]; ?></td>
                <td><?php echo $data["shipped_by"]; ?></td>
                <td><?php echo $data["tracking_number"]; ?></td>
                <td><?php echo $data["shipping_date"]; ?></td>
                <td><?php echo strtoupper($order->status); ?></td>
            </tr>
            <?php 
                }
            } 
            ?>
        </tbody>
    </table>
<?php         
    }
?>
</div>
<div class="d-lg-none pb-2">
<?php 
    if( $show == 'cards' ){
?>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Cards</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if( count( $consignment ) == 0 ){
            ?>
            <tr>
                <td class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $consignment as $card ){
                        $data = json_decode( $card->data, true );
            ?>
            <tr>
                <td>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Order ID</div>
                        <div class='col-sm-8'>
                            <?php echo $card->order_id + 1000; ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Player</div>
                        <div class='col-sm-8'>
                            <?php echo $data["player_name"] ?>								
                        </div>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-md-4'>Year</div>
                        <div class='col-sm-8'>
                            <?php echo $data["year"] ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Brand</div>
                        <div class='col-sm-8'>
                            <?php echo $data["brand"] ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Card #</div>
                        <div class='col-sm-8'>
                            <?php echo $data["card_number"] ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='small text-secondary col-sm-4'>Attribute SN</div>
                        <div class='col-sm-8'>
                            <?php echo $data["attribute_sn"] ?>
                        </div>
                    </div>
                </td>
            </tr>
            <?php 
                    }
                }
            ?>
        </tbody>
    </table>
<?php         
    } else {
?>
<?php         
    }
?>
</div>

