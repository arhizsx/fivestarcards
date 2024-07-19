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
                            <a class="btn border btn-dark ebayintegration-btn" data-action="showMemberInfoModal" data-user_id="<?php echo $user->ID; ?>">View</a>

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
                        if($user->roles[0] == "um_member" && $user->active == 0 ){
                            $total_users++;
                        }
                    }
                }
            ?>
            <H2 style="color: black;">Members (<?php echo $total_users;?>)</H2>    
            <button class="btn btn-outline-dark mb-3 ebayintegration-btn" data-action="newMember"  data-user_id="">New Member</button>
        
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
                            if($user->roles[0] == "um_member" && $user->active == 0 ){

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
                            <a class="btn border btn-dark ebayintegration-btn" data-action="showMemberInfoModal" data-user_id="<?php echo $user->ID; ?>">View</a>
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
    <div class="row">
        <div class="col-6">
        
            <H2 style="color: black;">Deactivated Members</H2>            
        </div>
        <div class="col-6 text-end">
            <input class="btn mt-3 px-2 search_box" style="text-align: left;" placeholder="Search" type="text" data-target="#members_table">
        </div>
    </div>

    <div class="table-responsive">    
        <table class='table 5star_my_orders table-bordered table-striped' id="deactivated_members_table">
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
                            if($user->roles[0] == "um_member" && $user->active == 1 ){
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
                            <button class="btn border btn-primary 5star_btn" data-action='reactivateUser' data-user_id='<?php echo $user->ID; ?>'>Reactivate</button>
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
	<div class="modal-dialog modal-xl" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-primary text-white">
				<strong>View Member</strong>
    			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
			</div>
            <div class="modal-body py-3 px-3">
                <div class="formbox">
                    <div class="btn-group w-100 mb-2 member_view_menu" role="group" aria-label="">
                        <button type="button" class="btn btn-outline-dark active ebayintegration-btn" data-action="getViewMemberDetails" data-user_id="">
                            Member Details
                        </button>
                        <button type="button" class="btn btn-outline-dark ebayintegration-btn" data-action="getViewMemberEbay" data-user_id="">
                            <i class="fa-brands fa-ebay fa-2xl"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark ebayintegration-btn" data-action="getViewMemberSKU" data-user_id="">
                            SKU
                        </button>
                        <button type="button" class="btn btn-outline-dark ebayintegration-btn" data-action="getViewUnmatchedSKU" data-user_id="">
                            Unmatched
                        </button>
                    </div>          
                    <div class="member_details_box boxes">
                        <div class="border p-3 mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Display Name</label>
                                    <input type="text" name="display_name" class="form-control p-2 mb-3" value="">
                                </div>
                                <div class="col-md-6">
                                    <label>Customer Number</label>
                                    <input type="text" name="customer_number" class="form-control p-2 mb-3" value="">
                                </div>
                                <div class="col-md-6">
                                    <label>Email</label>
                                    <input type="text" name="user_email" class="form-control p-2 mb-3" value="">
                                </div>
                            </div>
                        </div>          
                        <div class="text-end">
                            <button class="btn btn-outline-dark ebayintegration-btn" data-action="showMessageUserModal"  data-user_id="">Message</button>
                            <button class="btn btn-outline-dark ebayintegration-btn" data-action="loginToAccount"  data-user_id="">Login to Account</button>
                            <button class="btn btn-danger ebayintegration-btn" data-action="deactivateMember"  data-user_id="">Deactivate</button>
                            <button class="btn btn-primary ebayintegration-btn" data-action="saveMemberDetailsChanges" data-user_id="">Save Changes</button>
                        </div>
                    </div>
                    <div class="member_ebay_box boxes d-none">
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                    <th>Status</th>
                                    <th>Item</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                    </div>
                    <div class="member_sku_box boxes d-none">
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                    <th>SKU</th>
                                    <th class='fit'></th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                    </div>
                    <div class="member_unmatched_box boxes d-none">
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                    <th>SKU</th>
                                    <th class='fit text-end' style="width: 50px;"></th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                    </div>
                </div>
                <div class="d-none p-5 text-center loading">

                    Updating User Info, please wait...

                </div>
            </div>
		</div>
	</div>
</div>

<div class="modal fade message_user_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 0px; z-index: 99999">
	<div class="modal-dialog modal-xl" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-primary text-white">
				<strong>Message Member</strong>
    			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
			</div>
            <div class="modal-body py-3 px-3">
                <div class="formbox">
                    <form id="message_user_form">
                    <div class="border p-3 mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" value="messageUser" name="action" />
                                <label>Display Name</label>
                                <input type="text" name="display_name" class="form-control p-2 mb-3" value="">
                            </div>
                            <div class="col-md-6">
                                <label>Customer Number</label>
                                <input type="text" name="customer_number" class="form-control p-2 mb-3" value="">
                            </div>
                            <div class="col-md-6">
                                <label>Email</label>
                                <input type="text" name="user_email" class="form-control p-2 mb-3" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Subject</label>
                                <input type="text" name="subject" class="form-control p-2 mb-3" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Message</label>
                                <textarea rows="5" class="form-control" name="message"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Attachment</label>
                                <input type="file" class="form-control" name="attachment" />
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-outline-dark ebayintegration-btn" data-action="cancelSendMessage"  data-user_id="">Cancel</button>
                        <button class="btn btn-success ebayintegration-btn" data-action="sendUserMessage"  data-user_id="">Send</button>
                    </div>
                    </form>
                </div>
                <div class="d-none p-5 text-center loading">

                    Sending message, please wait...

                </div>
            </div>
		</div>
	</div>
</div>

<div class="modal fade new_user_modal" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 100px; z-index: 99999">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-primary text-white">
				<strong>New Member</strong>
    			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
			</div>
            <div class="modal-body py-3 px-3">
                <div class="formbox">                    
                    <form id="register_user_form">
                    <div class="error d-none mb-3 text-danger">
                        Error Creating New User
                    </div>
                    <div class="border p-3 mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" value="registerUser" name="action" />
                                <label>Full Name</label>
                                <input type="text" name="display_name" class="form-control p-2 mb-3" value="">
                            </div>
                            <div class="col-md-12">
                                <label>Email</label>
                                <input type="text" name="user_email" class="form-control p-2 mb-3" value="">
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="d-none p-5 text-center loading">

                    Registering user message, please wait...

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-dark" data-bs-dismiss="modal" >Cancel</button>
                <button class="btn btn-success ebayintegration-btn" data-action="registerUser"  data-user_id="">Register User</button>
            </div>
		</div>
	</div>
</div>



