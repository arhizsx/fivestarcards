<?php 

    print_r($atts);
?>

<div class="m-0 p-0">
    <div class="row">
        <div class="col-xl-6">
            <H1 style="color: black;">Administrators</H1>            
        </div>
        <div class="col-xl-6 text-end">
            
        </div>
    </div>
    <div class="table-responsive">    
        <table class='table 5star_my_orders table-bordered table-striped'>
            <thead>
                <tr>
                    <th>Customer #</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th class='text-end'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 

                    $args = array(
                        'orderby'    => 'display_name',
                        'order'      => 'ASC'
                    );   

                    $users = get_users( $args );


                    if($users){
                        foreach($users as $user){
                            if($user->roles[0] == "um_admin"){

                ?>
                    <tr>
                        <td>
                            <?php  echo $user->ID + 1000 ?>
                        </td>
                        <td>
                            <?php  echo $user->display_name ?>
                        </td>
                        <td>
                            <?php  echo $user->user_email ?>
                        </td>
                        <td class="text-end">
                            <button class="btn border btn-success 5star_btn" data-action='demote_admin' data-user_id='<?php echo $user->ID; ?>'>SKUs</button>
                        </td>
                    </tr>
                <?php    
                            }    
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
                        if($user->roles[0] == "um_member"){
                            $total_users++;
                        }
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
                    <th>Customer #</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th class='text-end'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 

                    if($users){
                        foreach($users as $user){
                            if($user->roles[0] == "um_member"){

                ?>
                    <tr>
                        <td>
                            <?php  echo $user->ID + 1000 ?>
                        </td>
                        <td>
                            <?php  echo $user->display_name ?>
                        </td>
                        <td>
                            <?php  echo $user->user_email ?>
                        </td>
                        <td class="text-end">
                            <button class="btn border btn-primary 5star_btn" data-action='make_admin' data-user_id='<?php echo $user->ID; ?>'>SKUs</button>
                        </td>
                    </tr>
                <?php    
                            }    
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