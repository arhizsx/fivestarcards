<style>
.table td.fit, 
.table th.fit {
   white-space: nowrap;
   width: 1%;
}    
H4 {
    color: black;
}
</style>

<?php 

global $wpdb;


    $consignment = $this->wpdb->get_results ( "

		SELECT 
            consignment.*,
            wp_users.user_email,
            wp_users.display_name
        FROM consignment
        	INNER JOIN wp_users
            ON consignment.user_id = wp_users.ID
        where order_id = " . $_GET["id"] . "
        order by order_id desc, consignment.id desc;
        "
    );

    $show = "cards";
    $btn_cards = 'btn-primary';
    $btn_orders = 'btn-secondary';

    $shipped = 0;
    $received = 0;
    $unavailable = 0;

     if( count( $consignment ) > 0 ){

        foreach( $consignment as $card ) {

            if( $card->status  == 'shipped') {
                $shipped++;
            }
            elseif( $card->status  == 'received') {
                $received++;
            }
            elseif( $card->status  == 'unavailable') {
                $unavailable++;
            }

        }
     }

?>

<div class="table-responsive">

    <H4>Shipped <?php echo $shipped ?></H4>
    <table class="table table-sm table-bordered" id="receiving_consignment">
        <thead>
            <tr>
                <th>Card ID</th>
                <th>Card</th>
                <th>Status</th>
                <th class="fit text-end"></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if(  $shipped == 0 ){
            ?>
            <tr>
                <td colspan="8" class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $consignment as $card ) {

                        if( $card->status == "shipped" ) {

                            $data = json_decode( $card->data, true );
            ?>
            <tr class="consigned_item_row" data-id="<?php echo $card->id ?>">

                <td><?php echo $card->id ?></td>
                <td><?php echo $data["year"] . " " . $data["brand"] . " " . $data["player"] . " " . $data["card_number"] . " " . $data["attribute_sn"]  ?></td>
                <td><?php echo $card->status ?></td>
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
                }
            ?>
        </tbody>
    </table>

    <H4>Received <?php echo $received ?></H4>
    <table class="table table-sm table-bordered" id="receiving_consignment">
        <thead>
            <tr>
                <th>Card ID</th>
                <th>Card</th>
                <th>Status</th>
                <th class="fit text-end"></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if( $received == 0 ){
            ?>
            <tr>
                <td colspan="8" class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $consignment as $card ) {
                        if( $card->status == "received" ) {

                            $data = json_decode( $card->data, true );
            ?>
            <tr class="consigned_item_row" data-id="<?php echo $card->id ?>">

                <td><?php echo $card->id ?></td>
                <td><?php echo $data["year"] . " " . $data["brand"] . " " . $data["player"] . " " . $data["card_number"] . " " . $data["attribute_sn"]  ?></td>
                <td><?php echo $card->status ?></td>
                <td class="fit">
                    <a class="btn btn-pill btn-sm btn-danger ebayintegration-btn" data-action="consignedCardNotReceived" data-id="<?php echo $card->id ?>" data-user_id="<?php echo get_current_user_id(); ?>">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </td>
            </tr>
            <?php 
                        }
                    }
                }
            ?>
        </tbody>
    </table>

    <H4>Unavailable  <?php echo $unavailable ?></H4>
    <table class="table table-sm table-bordered" id="receiving_consignment">
        <thead>
            <tr>
                <th>Card ID</th>
                <th>Card</th>
                <th>Status</th>
                <th class="fit text-end"></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if(  $unavailable == 0 ){
            ?>
            <tr>
                <td colspan="8" class="text-center py-5">
                    Empty
                </td>
            </tr>
            <?php 
                } else {
                    foreach( $consignment as $card ) {
                        if( $card->status == "unavailable" ) {

                            $data = json_decode( $card->data, true );
            ?>
            <tr class="consigned_item_row" data-id="<?php echo $card->id ?>">

                <td><?php echo $card->id ?></td>
                <td><?php echo $data["year"] . " " . $data["brand"] . " " . $data["player"] . " " . $data["card_number"] . " " . $data["attribute_sn"]  ?></td>
                <td><?php echo $card->status ?></td>
                <td class="fit">
                    <a class="btn btn-pill btn-sm btn-success ebayintegration-btn" data-action="confirmConsignedCardReceived" data-id="<?php echo $card->id ?>" data-user_id="<?php echo get_current_user_id(); ?>">
                        <i class="fa-solid fa-check"></i>
                    </a>
                </td>
            </tr>
            <?php 
                        }
                    }
                }
            ?>
        </tbody>
    </table>
</div>
