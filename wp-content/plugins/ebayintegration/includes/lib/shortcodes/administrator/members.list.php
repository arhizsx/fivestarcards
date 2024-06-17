<div class="m-0 p-0">
    <div class="row">
        <div class="col-xl-6">
            <H2 style="color: black;">Administrators</H2>            
        </div>
        <div class="col-xl-6 text-end">
            
        </div>
    </div>
    <div class="table-responsive">    
        <table class='table 5star_my_orders table-bordered table-striped 5star_table ' data-endpoint="<?php echo get_rest_url(null, "cards-grading/v1/table-action") ?>" data-nonce="<?php echo wp_create_nonce("wp_rest"); ?>">
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
            <H2 style="color: black;">Members (<?php echo $total_users;?>)</H2>            
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
                            <a class="btn border btn-dark ebayintegration-btn" data-action="showMemberInfoModal" data-id="<?php echo $user->ID; ?>">View</a>
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

<div class="modal fade member_info_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 0px; z-index: 99999">
	<div class="modal-dialog modal-lg" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-primary text-white">
				<strong>View Member</strong>
    			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
			</div>
            <div class="modal-body py-3 px-3">
                <div class="formbox">
                    <div class="btn-group w-100 mb-2 member_view_menu" role="group" aria-label="">
                        <button type="button" class="btn btn-outline-dark active ebayintegration-btn" data-action="getViewMemberDetails">
                            Member Details
                        </button>
                        <button type="button" class="btn btn-outline-dark ebayintegration-btn" data-action="getViewMemberEbay">
                            <i class="fa-brands fa-ebay fa-2xl"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark ebayintegration-btn" data-action="getViewMemberSKU">
                            SKU
                        </button>
                    </div>          
                    <div class="border p-3 mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Display Name</label>
                                <input type="text" class="form-control p-2 mb-3">
                            </div>
                            <div class="col-md-6">
                                <label>Customer Number</label>
                                <input type="text" class="form-control p-2 mb-3">
                            </div>
                            <div class="col-md-6">
                                <label>Email</label>
                                <input type="text" class="form-control p-2 mb-3">
                            </div>
                        </div>
                    </div>          
                    <div class="text-end">
                        <button class="btn btn-danger">Deactivate</button>
                        <button class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
                <div class="d-none p-5 text-center loading">

                    Updating User Info, please wait...

                </div>
            </div>
		</div>
	</div>
</div>
