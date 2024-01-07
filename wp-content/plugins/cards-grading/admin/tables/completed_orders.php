<?php

$user_id = get_current_user_id();

$args = array(
    'meta_query' => array(
        array(
            'key' => 'status',
            'value' => array("Consignment Paid", "Grading Paid", "Order Paid"),
            'compare' => 'IN'
        )
    ),
    'post_type' => 'cards-grading-chk',
    'posts_per_page' => -1
);

$posts = get_posts($args);

?>

<div class="m-0 p-0">
    <div class="row">
        <div class="col-xl-6">
            <H1 style="color: black;">Completed Orders</H1>            
        </div>
        <div class="col-xl-6 text-end">
            <input class="btn pl-2 search_box" style="text-align: left; padding-left: 10px; padding-bottom:5px; padding-top: 6px;" placeholder="Search" type="text" data-target=".5star_my_orders">        
        </div>
    </div>
    <div class="table-responsive">    
        <table class='table 5star_my_orders table-bordered table-striped'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Order #</th>
                    <th>Service Type</th>
                    <th>Status</th>
                    <th class='text-end'>Total Cards</th>
                    <th class='text-end'>Consigned Cards</th>
                    <th class='text-end'>Sold Price</th>
                    <th class='text-end'>Amt Paid</th>
                    <th class='text-end'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if( $posts ){

                        foreach($posts as $post)
                        {
                            $meta = get_post_meta($post->ID);

                            $date_format = get_option( 'date_format' );
                            $time_format = get_option( 'time_format' );

                            $user_id = $meta["user_id"][0];
                            $user = get_user_by( "id", $user_id );


                            $args = array(
                                'meta_query' => array(
                                    array(
                                        'key' => 'checkout_id',
                                        'value' => $post->ID,
                                    )
                                ),
                                'post_type' => 'cards-grading-card',
                                'posts_per_page' => -1
                            );
                            
                            $cards_list = get_posts($args);
                            $total_grading_charge = 0;
                            $consigned_cards = 0;
                            $total_to_receive = 0;
                            $total_sold = 0;

                            foreach($cards_list as $card_in_order){
                                $card_meta = get_post_meta($card_in_order->ID);
                                $card = json_decode($card_meta['card'][0], true);

                                if( $card_meta["status"][0] == "To Pay - Grade Only"){
                                    $total_grading_charge  = $total_grading_charge  + $card["per_card"];
                                }
                                
                                if( in_array($card_meta["status"][0], array("Consigned", "Sold - Consigned") )){
                                    $consigned_cards  = $consigned_cards  + 1;

                                    if($card_meta["status"][0] == "Sold - Consigned"){
                                        $total_to_receive  = $total_to_receive  + $card_meta["to_receive"][0];
                                        $total_sold  = $total_sold  + $card_meta["sold_price"][0];
                                    }

                                }
                                
                            }

                            $total_to_pay = $total_to_receive - $total_grading_charge;
                                                        
                ?>
                            <tr class="" data-post_id="<?php echo $post->ID; ?>">
                                <td><?php echo get_the_date( $date_format, $post->ID ) ?><br><span style='font-size:.7em !important;'><?php echo get_the_time( $time_format, $post->ID ); ?></span></td>
                                <td><?php echo $user->display_name; ?></td>
                                <td><?php echo $meta["order_number"][0]; ?></td>
                                <td><?php echo $meta["grading_type"][0]; ?><br><span style='font-size:.7em !important;'><?php echo  $meta["service_type"][0]; ?></span></td>
                                <td><?php echo $meta["status"][0]; ?></td>
                                <td class='text-end'><?php echo $meta["total_cards"][0]; ?></td>
                                <td class='text-end'><?php echo $consigned_cards; ?></td>
                                <td class='text-end'><?php echo "$" . number_format((float) $total_sold, 2, '.', ''); ?></td>
                                <td class='text-end'><?php echo "$" . number_format((float) $total_to_pay, 2, '.', ''); ?></td>
                                <td class="text-end">
                                    <button class="5star_btn btn btn-primary mb-3 admin-completed-row" data-action="admin_table_action"  data-post_id="<?php echo $post->ID; ?>">
                                        ...
                                    </button>           
                                </td>
                            </tr>
                <?php          
                        }
                    } else {
                ?>
                <tr>
                    <td class="text-center" colspan="10">Empty</td>
                </tr>
                <?php          
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>