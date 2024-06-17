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
        where status IN ('received', 'unavailable')
        order by order_id desc, status asc, consignment.id desc;
    ");

    $show = "cards";
    $btn_cards = 'btn-primary';
    $btn_orders = 'btn-secondary';

} else {

    $orders = $this->wpdb->get_results ( "
        SELECT
            consignment_orders.*,
            wp_users.user_email,
            wp_users.display_name
        FROM consignment_orders
        	INNER JOIN wp_users
            ON consignment_orders.user_id = wp_users.ID
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
                <th>Status</th>
                <th class="fit text-end"></th>
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
            <tr class="consigned_item_row" data-id="<?php echo $card->id ?>" >
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
                <td><?php echo strtoupper($card->status) ?></td>
                <td class="fit">
                    <?php 
                        if( $card->status == "received" ){
                    ?>
                    <a class="btn btn-pill btn-sm btn-primary ebayintegration-btn" data-action="postToEbayEditor" data-id="<?php echo $card->id ?>" data-user_id="<?php echo get_current_user_id()?>">
                        <i class="fa-solid fa-paper-plane"></i>
                    </a>
                    <a class="btn btn-pill btn-sm btn-dark ebayintegration-btn" data-action="showConsignedCardDetailsModal" data-id="<?php echo $card->id ?>" data-user_id="<?php echo get_current_user_id()?>">
                        <i class="fa-solid fa-gear"></i>
                    </a>
                    <?php 
                        }
                    ?>
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
                <td colspan="7" class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $orders as $order ) {
                        $data = json_decode( $order->data, true );
            ?>
                <tr>
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
                        <a class="btn btn-pill btn-sm btn-dark ebayintegration-btn" data-action="" data-id="<?php echo $order->id ?>">
                            <i class="fa-solid fa-gear"></i>
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

<div class="modal fade post_to_ebay_editor_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Post to eBay
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body py-2 px-3">
                <div class="row formbox">
                    <H1>Post to eBay</H1>
                    <p>coming soon</p>
                </div>
                <div class="d-none p-5 text-center loading">

                    posting item to eBay, please wait...

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
            </div>
		</div>
	</div>
</div>

<div class="modal fade consigned_card_details_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Consigned Card Details
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
            <div class="modal-body py-2 px-3">
                <div class="row formbox">
                    <H1>Post to eBay</H1>
                    <p>coming soon</p>
                </div>
                <div class="d-none p-5 text-center loading">

                    posting item to eBay, please wait...

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
            </div>
		</div>
	</div>
</div>
