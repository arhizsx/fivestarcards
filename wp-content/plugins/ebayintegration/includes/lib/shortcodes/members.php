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
            ?>
            <H1 style="color: black;">User SKUs</H1>            
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

                        $skus = get_user_meta( $user->ID, "sku", true );

                ?>
                    <tr>
                        <td>
                            <strong><?php  echo $user->display_name ?></strong><br>
                            <small><?php  echo $user->user_email ?></small><br>
                            <small><?php  echo $user->ID + 1000 ?></small>
                        </td>
                        <td>
                            <?php
                                if(count($skus) > 0){
                                    foreach($skus as $sku){
                                        echo "<li>". $sku . "</li>";
                                    }    
                                }
                            ?>
                        </td>
                        <td class="text-end">
                            <button class="btn border btn-primary ebayintegration-btn" 
                                data-action='addSKU' 
                                data-user_name='<?php  echo $user->display_name ?>' 
                                data-user_email='<?php  echo $user->user_email ?>' 
                                data-user_id='<?php echo $user->ID; ?>'
                            >
                                Add SKU
                            </button>
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

<div class="modal fade add_sku" tabindex="-1" role="dialog" aria-labelledby="dxmodal" aria-hidden="true"  data-backdrop="static" data-bs-backdrop="static"   data-bs-keyboard="false" data-data='' data-modal='' data-key='' data-modal_size='full' style="margin-top: 120px;">
	<div class="modal-dialog" id="dxmodal">
		<div class="modal-content modal-ajax">
			<div class="modal-header bg-dark text-white">
				<h5 class="modal-title">
					Add User SKU
				</h5>
    			<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
					X
				</button>
			</div>
                <div class="" id="view_card_form_box">
                    <div class="modal-body py-2 px-3">
                        <forn id="delete_order_form mb-3">
                            <input type="hidden" name="user_id" value=''/>
                            
                            <label>Name</label>
                            <input type="text" name="user_name"  class="form-control mb-2"/>
                            
                            <label>Email</label>
                            <input type="text" name="user_email"  class="form-control mb-2"/>
                            
                            <label>User ID</label>
                            <input type="text" name="id"  class="form-control mb-2"/>
                            
                            <label>eBay SKU</label>
                            <input type="text" name="sku"  class="form-control mb-2"/>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn border btn-secondary" data-bs-dismiss="modal" >Close</button>
                        <button class="btn border btn-primary ebayintegration" data-action="confirm_add_sku">Add SKU</button>
                    </div>
                </div>
		</div>
	</div>
</div>
