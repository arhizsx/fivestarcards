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
        <a class="btn btn-pill btn-sm mb-2 <?php echo $btn_cards; ?>" href="/administrator/consignment">Cards</a>
        <a class="btn btn-pill btn-sm mb-2 <?php echo $btn_orders; ?>" href="/administrator/consignment/?type=orders">Orders</a>
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
                <th>Card Number</th>
                <th>Player Name</th>
                <th>Attribute S/N</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-center py-5">
                    Empty
                </td>
            </tr>
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
                <th>Year</th>
                <th>Brand</th>
                <th>Card Number</th>
                <th>Player Name</th>
                <th>Attribute S/N</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" class="text-center py-5">
                    Empty
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php     
}
?>
