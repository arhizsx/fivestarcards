<?php

$user_id = get_current_user_id();

$args = array(
    'meta_query' => array(
        'relations' =>  'AND',    
        array(
            'key' => 'checkout_id',
            'value' => $params['order_number']
        ),
        array(
            'key' => 'user_id',
            'value' => $user_id
        )
    ),
    'post_type' => 'cards-grading-card',
    'posts_per_page' => -1
);

$posts = get_posts($args);
$grading_charge = 0;
$total_dv = 0;


?>

<div class="m-0 p-0">
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-3" >
            <div style="color: black !important; font-size: .7em;"><?php echo $params['title'] ?></div>
            <H1 style="color: black !important;"><?php echo $params['order_number'] ?></H1>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3" >
            <div style="color: black !important; font-size: .7em;">Status</div>
            <H1 style="color: black !important;">To Ship</H1>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6" >
            <div style="color: black !important; font-size: .7em;">Shipping Details</div>
        </div>
    </div>
    <div class="table-responsive">    
        <table class='table 5star_logged_cards table-bordered table-striped' data-grading_type="<?php echo $params['type'] ?>" data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/add-card") ?>" data-table_action_endpoint="<?php echo get_rest_url(null, "cards-grading/v1/table-action") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
            <thead>
                <tr>
                <th>Qty</th>
                <th>Year</th>
                <th>Brand</th>
                <th>Card #</th>
                <th>Player Name</th>
                <th class='text-end'>DV</th>
                <th class='text-end'>Total DV</th>
                <th class="text-end">Grading Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if( $posts ){

                        foreach($posts as $post)
                        {
                            $meta = get_post_meta($post->ID);
                            $card = json_decode($meta['card'][0], true);

                            $card_total_dv = $card["dv"] * $card["quantity"];
                            $card_grading_charge = $card["per_card"] * $card["quantity"];

                            $grading_charge = $grading_charge + $card_grading_charge;
                            $total_dv = $total_dv + $card_total_dv;

                ?>
                <tr class="card-row" data-post_id="<?php echo $post->ID; ?>" data-card='<?php echo json_encode($card) ?>'>
                    <td><?php echo $card["quantity"]; ?></td>
                    <td><?php echo $card["year"]; ?></td>
                    <td><?php echo $card["brand"]; ?></td>
                    <td><?php echo $card["card_number"]; ?><br><small><?php echo $card["attribute"]; ?></small></td>
                    <td><?php echo $card["player"]; ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float)$card["dv"], 2, '.', ''); ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float) $card_total_dv, 2, '.', ''); ?></td>
                    <td class='text-end'><?php echo "$" . number_format((float) $card_grading_charge, 2, '.', ''); ?></td>
                </tr>
                <?php          
                        }
                    } else {
                ?>
                <tr>
                    <td class="text-center" colspan="9">Empty</td>
                </tr>
                <?php          
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>