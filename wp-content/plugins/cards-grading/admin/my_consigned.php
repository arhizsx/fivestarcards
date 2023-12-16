<?php

$user_id = get_current_user_id();

$args = array(
    'meta_query' => array(
        'relation' => 'AND', 
        array(
            'key' => 'user_id',
            'value' => $user_id
        ),
        array(
            'key' => 'status',
            'value' => array("Order To Pay"),
            'compare' => 'IN'
        )

    ),
    'post_type' => 'cards-grading-chk',
    'posts_per_page' => -1
);

$posts = get_posts($args);

?>

<div class="m-0 p-0">
    <H3 style="color: black;">Consigned Orders</H3>
    <div class="table-responsive">    
        <table class='table 5star_my_orders table-bordered table-striped'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Order #</th>
                    <th>Service Type</th>
                    <th>Status</th>
                    <th class='text-end'>Total DV</th>
                    <th class='text-end'>Total Cards</th>
                    <th class='text-end'>Consigned Cards</th>
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
                                                        
                ?>
                <tr class="my-order-row" data-post_id="<?php echo $post->ID; ?>">
                    <td><?php echo get_the_date( $date_format, $post->ID ) ?><br><span style='font-size:.7em !important;'><?php echo get_the_time( $time_format, $post->ID ); ?></span></td>
                    <td><?php echo $meta["order_number"][0]; ?></td>
                    <td><?php echo $meta["service_type"][0]; ?><br><span style='font-size:.7em !important;'><?php echo $meta["grading_type"][0]; ?></span></td>
                    <td><?php echo $meta["status"][0]; ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float) $meta["total_dv"][0], 2, '.', ''); ?></td>
                    <td class='text-end'><?php echo $meta["total_cards"][0]; ?></td>
                    <td class='text-end'>0</td>
                </tr>
                <?php          
                        }
                    } else {
                ?>
                <tr>
                    <td class="text-center" colspan="6">Empty</td>
                </tr>
                <?php          
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>