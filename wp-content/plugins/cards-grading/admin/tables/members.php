<div class="m-0 p-0">
    <div class="row">
        <div class="col-xl-6">
            <H1 style="color: black;">Administrators</H1>            
        </div>
        <div class="col-xl-6 text-end">
            
        </div>
    </div>
    <div class="table-responsive">    
        <table class='table 5star_my_orders table-bordered table-striped 5star_table ' data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/order-action") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
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
                            <button class="btn border btn-success 5star_btn" data-action='demote_admin' data-user_id='<?php echo $user->ID; ?>'>Demote</button>
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
                            <button class="btn border btn-dark 5star_btn" data-action='view_account' data-user_id='<?php echo $user->ID; ?>'>View</button>
                            <button class="btn border btn-primary 5star_btn" data-action='make_admin' data-user_id='<?php echo $user->ID; ?>'>Promote</button>
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

<div class="modal fade view_account" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog modal-xl" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					View Account
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="view_card_form_box">
                    <div class="modal-body py-2 px-3">
                        <H5 style="color: black;">User Information</H5>
                        <H5 style="color: black;">SKUs</H5>
                        <H5 style="color: black;">Active Items</H5>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                        <button class="btn border btn-danger 5star_btn" data-action="delete_user">Delete</button>
                        <button class="btn border btn-primary 5star_btn" data-action="update_user_info">Save Changes</button>
                    </div>
                </div>
		</div>
	</div>
</div>