<?php

$user_id = get_current_user_id();

$meta_query = array(
    "relaton" => 'AND',
);

array_push(
    $meta_query,             
    array(
        'key' => 'status',
        'value' => array("Processing Order", "Shipped to PSA / SGC", "Research", "Grading", "Assembly", "QA1", "QA2", "Cards Graded", "Grading Complete", "Completed - Grades Ready"),
        'compare' => 'IN'
    )
);


if(isset( $_GET['filtered']) && $_GET["filtered"] == "true"){

    if(isset( $_GET["submission_number"]) ){        
        $filter_array = array(
            "key" => 'submission_number',
            'value' => $_GET["submission_number"],
        );

        array_push(
            $meta_query,             
            $filter_array,
        );

    }

    if(isset( $_GET["user_id"]) ){
        
        $filter_array = array(
            "key" => 'user_id',
            'value' => $_GET["user_id"],
        );

    }
    
    if(isset( $_GET["status"]) ){
        
        $filter_array = array(
            "key" => 'status',
            'value' => $_GET["status"],
        );

    }

    if(isset( $_GET["grading_type"]) ){
        
        $filter_array = array(
            "key" => 'grading_type',
            'value' => $_GET["grading_type"],
        );

    }


    array_push(
        $meta_query,             
        $filter_array,
    );

}

$args = array(
    'meta_query' => array(
        $meta_query
    ),
    'post_type' => 'cards-grading-chk',
    'posts_per_page' => -1
);

$posts = get_posts($args);

?>

<div class="m-0 p-0">
<div class="row">
        <div class="col-xl-6">
            <H1 style="color: black;">Order Receiving</H1>            
        </div>
        <div class="col-xl-6 text-end">
        <?php if( isset( $_GET["filtered"] ) == "true") { ?>

            Filtered By: 
            <?php if(isset($_GET["user_id"])){ ?>
                <button class="5star_btn btn btn-danger mb-3 py-0 px-2 mt-3 btn-sm" data-action="remove_filter">
                    Customer
                </button>           
            <?php } ?>
            <?php if(isset($_GET["status"])){ ?>
                <button class="5star_btn btn btn-danger mb-3 py-0 px-2 mt-3 btn-sm" data-action="remove_filter">
                    Status
                </button>           
            <?php } ?>
            <?php if(isset($_GET["grading_type"])){ ?>
                <button class="5star_btn btn btn-danger mb-3 py-0 px-2 mt-3 btn-sm" data-action="remove_filter">
                    Grading Type
                </button>           
            <?php } ?>
            <?php if( isset($_GET["submission_number"])){ ?>
                <button class="5star_btn btn btn-danger mb-3 py-0 px-2 mt-3 mr-5  btn-sm" data-action="remove_filter">
                    Submission #
                </button>
            <?php } ?>
            <button class='5star_btn btn btn-secondary mb-3 btn-sm py-0 mt-3 px-2 ml-3' data-action="remove_filter" data-order_number="<?php echo $params['order_number'] ?>">
                Clear
            </button>      
            <?php if( isset($_GET["submission_number"])){ ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; New Status: 
                <select name="multi_update_status_select " class="mt-3" style="font-size: 13px;" >
                    <option value="">Select New Status</option>
                    <option value="Processing Order">Processing Order</option>
                    <option value="Shipped to PSA / SGC">Shipped to PSA / SGC</option>
                    <option value="Research">Research</option>
                    <option value="Grading">Grading</option>
                    <option value="Assembly">Assembly</option>
                    <option value="QA1">QA1</option>
                    <option value="QA2">QA2</option>
                    <option value="Completed - Grades Ready">Completed - Grades Ready</option>
                </select>
                <button class='5star_btn btn btn-secondary mb-3 btn-sm py-0 mt-3 px-2 ml-3' data-action="multi_update_status" data-order_number="<?php echo $params['order_number'] ?>">
                    Apply
                </button>      
            <?php } ?>
        <?php } ?>
        </div>
    </div>
    <?php 
        if( $posts ){
            
            $customers = [];
            $status = [];
            $grading_types = [];

            foreach($posts as $post)
            {
                $meta = get_post_meta($post->ID);

                $user_id = $meta["user_id"][0];
                $user = get_user_by( "id", $user_id );

                $exists = false;
                foreach($customers as $cx){
                    if( $cx["user_id"] == $user_id){
                        $exists = true;
                        break;
                    }
                }

                if($exists == false){
                    array_push($customers, ["customer"=> ucfirst($user->display_name), "user_id" => $user_id]);
                }


                $exists = false;
                foreach($status as $st){
                    if( $st["status"] == $meta["status"][0]){
                        $exists = true;
                        break;
                    }
                }

                if($exists == false){
                    array_push($status, ["status"=> ucfirst($meta["status"][0])]);
                }

                $exists = false;
                foreach($grading_types as $gd){
                    if( $gd["grading_type"] == $meta["grading_type"][0]){
                        $exists = true;
                        break;
                    }
                }

                if($exists == false){
                    array_push($grading_types, ["grading_type"=> $meta["grading_type"][0]]);
                }

            }
        }
    ?>
    <div class="table-responsive">    
        <table class='table 5star_my_orders table-bordered table-striped'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>
                        <select name="select_customer_filter " class="select_filter w-100 px-2 py-0" data-filter="user_id" style="font-size: 15px; margin-left: -10px; font-weight: bold; border: 0px;" >
                            <option value="">Customer</option>
                            <?php 
                                foreach($customers as $cx){
                            ?>
                            <option value="<?php echo $cx["user_id"]; ?>"><?php echo $cx["customer"]; ?></option>
                            <?php 
                                }
                            ?>
                        </select>

                    </th>
                    <th>Order #</th>
                    <th>
                        <select name="select_grading_filter " class="select_filter w-100 px-2 py-0" data-filter="grading_type" style="font-size: 15px; margin-left: -10px; font-weight: bold; border: 0px;" >
                            <option value="">Grading Type</option>
                            <?php 
                                foreach($grading_types as $gd){
                            ?>
                            <option value="<?php echo $gd["grading_type"]; ?>"><?php echo $gd["grading_type"]; ?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </th>
                    <th>
                        <select name="select_status_filter " class="select_filter w-100 px-2 py-0" data-filter="status" style="font-size: 15px; margin-left: -10px; font-weight: bold; border: 0px;" >
                            <option value="">Status</option>
                            <?php 
                                foreach($status as $st){
                            ?>
                            <option value="<?php echo $st["status"]; ?>"><?php echo $st["status"]; ?></option>
                            <?php 
                                }
                            ?>
                        </select>
                    </th>
                    <th class='text-end'>Total Cards</th>
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
                                                        
                ?>
                <tr class="" data-post_id="<?php echo $post->ID; ?>">
                    <td><?php echo get_the_date( $date_format, $post->ID ) ?><br><span style='font-size:.7em !important;'><?php echo get_the_time( $time_format, $post->ID ); ?></span></td>
                    <td><a class="filter-links" href='/admin/order-receiving/?filtered=true&show=open&user_id=<?php echo $user_id; ?>'> <?php echo $user->display_name; ?></a> <br> <small style="font-size: 11px;"><?php echo $user_id + 1000; ?></small></td>
                    <td><?php echo $meta["order_number"][0]; ?></td>
                    <td><a class="filter-links" href='/admin/order-receiving/?filtered=true&show=open&grading_type=<?php echo $meta["grading_type"][0]; ?>'><?php echo $meta["grading_type"][0]; ?></a><br><span style='font-size:.7em !important;'><?php echo  $meta["service_type"][0]; ?></span></td>
                    <td><a class="filter-links" href='/admin/order-receiving/?filtered=true&show=open&status=<?php echo $meta["status"][0]; ?>'><?php echo $meta["status"][0]; ?></a></td>
                    <td class='text-end'><?php echo $meta["total_cards"][0]; ?></td>
                    <td class="text-end">
                        <button class="5star_btn btn btn-primary mb-3 admin-order-row" data-action="admin_table_action"  data-post_id="<?php echo $post->ID; ?>">
                            ...
                        </button>           
                    </td>
                </tr>
                <?php          
                        }
                    } else {
                ?>
                <tr>
                    <td class="text-center" colspan="7">Empty</td>
                </tr>
                <?php          
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>