<?php

$user_id = get_current_user_id();

$args = array(
    'meta_query' => array(
        array(
            'key' => 'user_id',
            'value' => $user_id
        )
    ),
    'post_type' => 'cards-grading-chk',
    'posts_per_page' => -1
);

$posts = get_posts($args);

?>

<div class="m-0 p-0">
    <div class="table-responsive">    
        <table class='table 5star_logged_cards table-bordered table-striped' data-table_action_endpoint="<?php echo get_rest_url(null, "cards-grading/v1/table-action") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Order #</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th class='text-end'>Total Cards</th>
                    <th class='text-end'>Total DV</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if( $posts ){

                        foreach($posts as $post)
                        {
                            $meta = get_post_meta($post->ID);
                ?>
                <tr class="card-row" data-post_id="<?php echo $post->ID; ?>">
                    <td>X</td>
                    <td><?php echo $meta["order_number"][0]; ?></td>
                    <td><?php echo $meta["service_type"][0]; ?><br><span style='font-size:.7em !important;'><?php echo $meta["grading_type"][0]; ?></span></td>
                    <td>X</td>
                    <td>X</td>
                    <td>X</td>
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