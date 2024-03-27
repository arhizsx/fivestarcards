<div class="m-0 p-0">

    <div class="row">
        <div class="col-6">
            <?php 

                $args = array(
                    'orderby'    => 'display_name',
                    'order'      => 'ASC'
                );

                $users = get_users( $args );

                $total_users = 0;

                if($users){
                    foreach($users as $user){
                            $total_users++;
                    }
                }
            ?>
            <H1 style="color: black;">Members (<?php echo $total_users;?>)</H1>            
        </div>
        <div class="col-6 text-end">
            <input class="btn mt-3 px-2 search_box" style="text-align: left;" placeholder="Search" type="text" data-target="#members_table">
        </div>
    </div>
    <div class="table-responsive">    
        <table class='table 5star_my_orders table-bordered table-striped' id="members_table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>SKUs</th>
                    <th class='text-end'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 

                    if($users){                        
                        foreach($users as $user){

                        $all_meta_for_user = get_user_meta( $user->ID );

                ?>
                    <tr>
                        <td>
                            <strong><?php  echo $user->display_name ?></strong><br>
                            <small><?php  echo $user->user_email ?></small><br>
                            <small><?php  echo $user->ID + 1000 ?></small>
                        </td>
                        <td>
                            <?php
                                print_r($all_meta_for_user['nickname']);
                            ?>
                        </td>
                        <td class="text-end">
                            <button class="btn border btn-primary 5star_btn" data-action='make_admin' data-user_id='<?php echo $user->ID; ?>'>Add SKU</button>
                        </td>
                    </tr>
                <?php    
                        }
                    } else {
                ?>
                    <tr>
                        <td class="text-center" colspan="4">Empty</td>
                    </tr>
                <?php                  
                    }
                ?>                
            </tbody>
        </table>
    </div>
</div>